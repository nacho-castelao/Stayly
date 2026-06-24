<?php

require_once BASE_PATH . '/controllers/BaseController.php';
require_once BASE_PATH . '/models/Booking.php';
require_once BASE_PATH . '/models/Payment.php';
require_once BASE_PATH . '/models/Property.php';

class PaymentController extends BaseController
{
    private Booking $bookingModel;
    private Payment $paymentModel;

    public function __construct()
    {
        $this->bookingModel = new Booking();
        $this->paymentModel = new Payment();
    }

    public function index(): void
    {
    }

    /**
     * Payment summary for an awaiting_payment booking. Reached from
     * BookingController::create after a stay is reserved.
     */
    public function show(): void
    {
        $this->requireAuth('index.php');

        $bookingId = filter_input(INPUT_GET, 'booking_id', FILTER_VALIDATE_INT);
        $booking = $this->loadOwnPayableBooking($bookingId);

        // Enrich the summary with the property and its cover image so the
        // checkout shows what's being booked, not just raw dates.
        $propertyModel = new Property();
        $propertyModel->setId((int) $booking['property_id']);
        $property = $propertyModel->getOne() ?: [];

        $mainImage = null;
        $images = $propertyModel->getImages();
        while ($img = $images->fetch()) {
            if ((int) $img['is_main'] === 1) {
                $mainImage = $img['image_url'];
                break;
            }
            $mainImage = $mainImage ?? $img['image_url'];
        }

        require_once BASE_PATH . '/views/layout/header.php';
        require_once BASE_PATH . '/views/payment/show.php';
        require_once BASE_PATH . '/views/layout/footer.php';
    }

    /**
     * Start a real Stripe Checkout for an awaiting_payment booking. Creates a
     * Checkout Session (hosted card page), records an 'initiated' payment row
     * keyed to the session id, then sends the user to Stripe. The booking is
     * NOT confirmed here — that happens in fulfill() once Stripe reports the
     * payment as paid (via the webhook, with the success page as a fallback).
     */
    public function process(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            $this->redirect('public/index.php');
        }

        $this->requireAuth('index.php');

        $bookingId = filter_input(INPUT_POST, 'booking_id', FILTER_VALIDATE_INT);
        $booking = $this->loadOwnPayableBooking($bookingId);

        if (!STRIPE_SECRET_KEY) {
            $this->loadToast('error', 'Payments are not configured. Please try again later.');
            $this->redirect("public/Payment/show?booking_id=$bookingId");
        }

        // Enrich the Stripe line item with the property title so the customer
        // sees what they're paying for on the hosted checkout page.
        $propertyModel = new Property();
        $propertyModel->setId((int) $booking['property_id']);
        $property = $propertyModel->getOne() ?: [];
        $title = $property['title'] ?? 'Your stay';

        // Stripe deals in the smallest currency unit (cents for EUR).
        $amountCents = (int) round((float) $booking['total_price'] * 100);

        try {
            \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

            $session = \Stripe\Checkout\Session::create([
                'mode' => 'payment',
                'line_items' => [[
                    'quantity' => 1,
                    'price_data' => [
                        'currency' => PAYMENT_CURRENCY,
                        'unit_amount' => $amountCents,
                        'product_data' => ['name' => $title],
                    ],
                ]],
                // {CHECKOUT_SESSION_ID} is a literal placeholder Stripe fills in
                // on redirect — do not urlencode it.
                'success_url' => DEFAULT_URL . 'public/Payment/success?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => DEFAULT_URL . "public/Payment/cancel?booking_id=$bookingId",
                'client_reference_id' => (string) $booking['id'],
                'metadata' => ['booking_id' => (string) $booking['id']],
            ]);

            // Record the attempt so the webhook/success page can map the
            // session back to this booking. Status stays 'initiated' until paid.
            $this->paymentModel->setBooking_id((int) $booking['id']);
            $this->paymentModel->setAmount((float) $booking['total_price']);
            $this->paymentModel->setStatus('initiated');
            $this->paymentModel->setProvider('stripe');
            $this->paymentModel->setProvider_ref($session->id);
            $this->paymentModel->create();
        } catch (Throwable $e) {
            error_log('Stripe checkout creation failed: ' . $e->getMessage());
            $this->loadToast('error', 'We could not start the payment. Please try again.');
            $this->redirect("public/Payment/show?booking_id=$bookingId");
        }

        // Hand off to Stripe's hosted checkout (an absolute, external URL, so we
        // emit the Location header directly rather than via redirect()).
        header('Location: ' . $session->url, true, 303);
        exit;
    }

    /**
     * Return target for Stripe's success_url. Verifies the session is paid and
     * fulfills the booking (idempotent — the webhook may have done it already),
     * then sends the user back to the listing with a flash toast.
     */
    public function success(): void
    {
        $this->requireAuth('index.php');

        $sessionId = $_GET['session_id'] ?? null;
        if (!$sessionId || !STRIPE_SECRET_KEY) {
            $this->loadToast('error', 'We could not verify your payment.');
            $this->redirect('public/index.php');
        }

        // Map the session back to our payment/booking and enforce ownership so
        // one user can't drive another user's confirmation page.
        $payment = $this->paymentModel->findByProviderRef($sessionId);
        $booking = $payment ? $this->bookingModel->getById((int) $payment['booking_id']) : false;

        if (!$booking || (int) $booking['user_id'] !== (int) $_SESSION['user_id']) {
            $this->loadToast('error', 'We could not find that payment.');
            $this->redirect('public/index.php');
        }

        try {
            \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            $confirmed = $this->fulfill($session);
        } catch (Throwable $e) {
            error_log('Stripe success verification failed: ' . $e->getMessage());
            $confirmed = false;
        }

        if ($confirmed) {
            $this->loadToast('success', 'Payment complete — your booking is confirmed!');
        } else {
            // Paid-but-not-yet-settled (async method) or verification hiccup:
            // the webhook is the source of truth and will finish the job.
            $this->loadToast('info', "Thanks! We're confirming your payment — your booking will update shortly.");
        }

        $this->redirect('public/Property/showOne?id=' . (int) $booking['property_id']);
    }

    /**
     * Return target for Stripe's cancel_url. The booking stays awaiting_payment
     * so the user can retry from the summary page.
     */
    public function cancel(): void
    {
        $this->requireAuth('index.php');

        $bookingId = filter_input(INPUT_GET, 'booking_id', FILTER_VALIDATE_INT);

        $this->loadToast('info', 'Payment cancelled — your reservation is still held. You can complete it anytime.');

        if ($bookingId) {
            $this->redirect("public/Payment/show?booking_id=$bookingId");
        }
        $this->redirect('public/index.php');
    }

    /**
     * Stripe webhook receiver (server-to-server, no user session). Verifies the
     * event signature, then fulfills the booking on checkout.session.completed.
     * This is the authoritative confirmation path; the success page is a
     * best-effort fallback that calls the same idempotent fulfill().
     */
    public function webhook(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            http_response_code(405);
            exit;
        }

        $payload = file_get_contents('php://input');
        $sig = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

        if (!STRIPE_WEBHOOK_SECRET) {
            error_log('Stripe webhook hit but STRIPE_WEBHOOK_SECRET is not set.');
            http_response_code(500);
            exit;
        }

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sig, STRIPE_WEBHOOK_SECRET);
        } catch (Throwable $e) {
            // Bad payload or signature — reject so Stripe retries / flags it.
            error_log('Stripe webhook signature verification failed: ' . $e->getMessage());
            http_response_code(400);
            exit;
        }

        if ($event->type === 'checkout.session.completed') {
            try {
                $this->fulfill($event->data->object);
            } catch (Throwable $e) {
                // Returning 500 makes Stripe retry the delivery later.
                error_log('Stripe webhook fulfillment failed: ' . $e->getMessage());
                http_response_code(500);
                exit;
            }
        }

        http_response_code(200);
        echo json_encode(['received' => true]);
    }

    /**
     * Settle a paid Stripe Checkout Session: flip the payment to 'paid' and the
     * booking awaiting_payment -> confirmed, in one transaction. Idempotent and
     * safe to call from both the webhook and the success page — duplicate calls
     * are no-ops thanks to the status guards. Returns true when the booking is
     * confirmed (now or already). Returns false if the session isn't paid, the
     * amount doesn't match, or the session is unknown.
     */
    private function fulfill(\Stripe\Checkout\Session $session): bool
    {
        // Only settle a genuinely paid session.
        if (($session->payment_status ?? null) !== 'paid') {
            return false;
        }

        $payment = $this->paymentModel->findByProviderRef($session->id);
        if (!$payment) {
            error_log('Stripe fulfill: no payment for session ' . $session->id);
            return false;
        }

        // Anti-tamper: the amount Stripe charged must match what we recorded.
        $expectedCents = (int) round((float) $payment['amount'] * 100);
        if ((int) ($session->amount_total ?? -1) !== $expectedCents) {
            error_log("Stripe fulfill: amount mismatch for session {$session->id} "
                . "(expected $expectedCents, got " . ($session->amount_total ?? 'null') . ')');
            return false;
        }

        // Already settled by a prior call (webhook + redirect race) — done.
        if ($payment['status'] === 'paid') {
            return true;
        }

        $db = Database::connect();
        $db->beginTransaction();
        try {
            $this->paymentModel->markPaid($session->id);

            // confirm() is status-guarded; false here just means it was already
            // confirmed, which is fine — the payment is what we just settled.
            $this->bookingModel->setId((int) $payment['booking_id']);
            $this->bookingModel->confirm();

            $db->commit();
        } catch (Throwable $e) {
            $db->rollBack();
            throw $e;
        }

        return true;
    }

    /**
     * Load a booking and enforce that it belongs to the current user and is
     * still awaiting payment. Redirects (which exits) on any failure, so a
     * returned value is always a valid, payable booking owned by the user.
     */
    private function loadOwnPayableBooking(int|false|null $bookingId): array
    {
        if (!$bookingId) {
            $this->loadToast('error', 'Booking not found.');
            $this->redirect('public/index.php');
        }

        $booking = $this->bookingModel->getById($bookingId);

        if (!$booking || (int) $booking['user_id'] !== (int) $_SESSION['user_id']) {
            $this->loadToast('error', 'Booking not found.');
            $this->redirect('public/index.php');
        }

        if ($booking['status'] !== 'awaiting_payment') {
            $this->loadToast('error', 'This booking is not awaiting payment.');
            $this->redirect('public/index.php');
        }

        return $booking;
    }
}
