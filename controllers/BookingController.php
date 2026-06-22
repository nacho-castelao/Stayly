<?php

require_once BASE_PATH . '/controllers/BaseController.php';
require_once BASE_PATH . '/models/Booking.php';
require_once BASE_PATH . '/models/Property.php';
require_once BASE_PATH . '/models/DateRange.php';

class BookingController extends BaseController
{
    private Booking $bookingModel;
    private Property $propertyModel;

    public function __construct()
    {
        $this->bookingModel = new Booking();
        $this->propertyModel = new Property();
    }

    public function index()
    {
    }

    /**
     * Handle the property page's booking form POST: validate the requested
     * stay server-side, price it, reserve it (status awaiting_payment), then
     * redirect back (PRG) with a flash toast. The datepicker sends start_date
     * and end_date as YYYY-MM-DD hidden inputs alongside property_id and guests.
     */
    public function create()
    {
        // Only a POST creates a booking; a stray GET just goes home.
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            $this->redirect('public/index.php');
        }

        $propertyId = filter_input(INPUT_POST, 'property_id', FILTER_VALIDATE_INT);
        $guests = filter_input(INPUT_POST, 'guests', FILTER_VALIDATE_INT);
        $startRaw = $_POST['start_date'] ?? null;
        $endRaw = $_POST['end_date'] ?? null;

        // Where to send the user back to on success or any validation failure.
        $back = $propertyId
            ? "public/Property/showOne?id=$propertyId"
            : 'public/index.php';

        // Must be logged in to book (mirrors the protected-action pattern).
        $this->requireAuth($back);

        if (!$propertyId) {
            $this->loadToast('error', 'We could not find that property.');
            $this->redirect('public/index.php');
        }

        // Property must exist — this also gives us price and capacity.
        $this->propertyModel->setId($propertyId);
        $property = $this->propertyModel->getOne();

        if (!$property) {
            $this->loadToast('error', 'We could not find that property.');
            $this->redirect('public/index.php');
        }

        // Authoritative date validation — the client checks are UX only.
        $range = DateRange::fromStrings($startRaw, $endRaw);

        if (!$range->isValid()) {
            $this->loadToast('error', $range->firstError());
            $this->redirect($back);
        }

        // Guests within the property's capacity.
        if (!$guests || $guests < 1 || $guests > (int) $property['max_guests']) {
            $this->loadToast('error', 'Please choose a valid number of guests.');
            $this->redirect($back);
        }

        // Host-blocked days (availability table). Occupancy by other bookings is
        // checked atomically below in createIfAvailable().
        if ($this->propertyModel->isRangeBlocked($range->startIso(), $range->endIso())) {
            $this->loadToast('error', 'Some of those dates are not available for this property.');
            $this->redirect($back);
        }

        $total = $range->nights() * (float) $property['price_per_night'];

        // Populate the booking, then check availability + insert atomically.
        $this->bookingModel->setUser_id($_SESSION['user_id']);
        $this->bookingModel->setProperty_id($propertyId);
        $this->bookingModel->setStart_date($range->startIso());
        $this->bookingModel->setEnd_date($range->endIso());
        $this->bookingModel->setGuests($guests);
        $this->bookingModel->setTotal_price($total);
        $this->bookingModel->setStatus('awaiting_payment');

        try {
            $bookingId = $this->bookingModel->createIfAvailable();
        } catch (Throwable $e) {
            $this->loadToast('error', 'Something went wrong creating your booking. Please try again.');
            $this->redirect($back);
        }

        if ($bookingId === 0) {
            $this->loadToast('error', 'Those dates are no longer available for this property.');
            $this->redirect($back);
        }

        // Hand off to payment for this awaiting_payment booking.
        $this->loadToast('success', 'Booking reserved! Complete payment to confirm.');
        $this->redirect("public/Payment/show?booking_id=$bookingId");
    }
}
