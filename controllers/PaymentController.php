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

    public function index()
    {
    }

    /**
     * Payment summary for an awaiting_payment booking. Reached from
     * BookingController::create after a stay is reserved.
     */
    public function show()
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
     * Settle payment for a booking and confirm it. The actual charge is a stub:
     * with a real provider (Stripe/Redsys) the payment row would be created as
     * 'initiated' and flipped to 'paid' on the provider's callback. Here we
     * record it as paid and confirm the booking in one transaction so the two
     * never drift apart.
     */
    public function process()
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            $this->redirect('public/index.php');
        }

        $this->requireAuth('index.php');

        $bookingId = filter_input(INPUT_POST, 'booking_id', FILTER_VALIDATE_INT);
        $booking = $this->loadOwnPayableBooking($bookingId);

        $db = Database::connect();
        $db->beginTransaction();
        try {
            $this->paymentModel->setBooking_id((int) $booking['id']);
            $this->paymentModel->setAmount((float) $booking['total_price']);
            $this->paymentModel->setStatus('paid'); // stub: real charge happens here
            $this->paymentModel->create();

            $this->bookingModel->setId((int) $booking['id']);
            if (!$this->bookingModel->confirm()) {
                // Status changed under us (already confirmed/cancelled).
                $db->rollBack();
                $this->loadToast('error', 'This booking could not be confirmed.');
                $this->redirect('public/index.php');
            }

            $db->commit();
        } catch (Throwable $e) {
            $db->rollBack();
            $this->loadToast('error', 'Payment failed. Please try again.');
            $this->redirect("public/Payment/show?booking_id=$bookingId");
        }

        $this->loadToast('success', 'Payment complete — your booking is confirmed!');
        $this->redirect('public/Property/showOne?id=' . (int) $booking['property_id']);
    }

    /**
     * Load a booking and enforce that it belongs to the current user and is
     * still awaiting payment. Redirects (which exits) on any failure, so a
     * returned value is always a valid, payable booking owned by the user.
     */
    private function loadOwnPayableBooking($bookingId): array
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
