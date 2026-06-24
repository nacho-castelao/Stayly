<?php

require_once BASE_PATH . '/controllers/BaseController.php';
require_once BASE_PATH . '/models/Review.php';

/**
 * Guest-facing review endpoints. Both actions are AJAX/JSON (the review form is
 * a modal in "My Bookings"): they read the raw JSON body, re-validate
 * everything server-side, and echo {"status": "success"|"error", ...}. The
 * read side (property page, host rating) is served by PropertyController via the
 * Review model — this controller only handles writes.
 */
class ReviewController extends BaseController
{
    private Review $reviewModel;

    public function __construct()
    {
        $this->reviewModel = new Review();
    }

    public function index()
    {
        // No standalone index view; reviews are surfaced on the property page
        // and in the dashboard. Bounce stray hits home.
        $this->redirect('public/Home/index');
    }

    /**
     * Create a review for a completed booking. A guest may review only when:
     * the booking is theirs, payment succeeded, the checkout date has passed,
     * the stay isn't their own listing, and it hasn't been reviewed yet. The
     * property/host links are derived from the booking server-side, so a client
     * can't attribute a review to a property it never stayed at.
     */
    public function create()
    {
        $this->requireAuth();
        header('Content-Type: application/json');

        $userId = (int) $_SESSION['user_id'];
        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        $bookingId = filter_var($data['booking_id'] ?? null, FILTER_VALIDATE_INT);
        if (!$bookingId) {
            return $this->fail('We could not find that booking.');
        }

        $context = $this->reviewModel->reviewableContext($bookingId, $userId);

        // Booking missing or not owned by this user — same opaque message either
        // way so we don't leak which bookings exist.
        if (!$context) {
            return $this->fail('We could not find that booking.', 404);
        }
        if (!$context['paid']) {
            return $this->fail('You can review a stay once its payment is complete.');
        }
        if ($context['end_date'] >= date('Y-m-d')) {
            return $this->fail('You can leave a review after your stay has finished.');
        }
        if ((int) $context['host_id'] === $userId) {
            return $this->fail('You cannot review your own listing.', 403);
        }
        if (!empty($context['review_id'])) {
            return $this->fail('You have already reviewed this stay.', 409);
        }

        $ratings = $this->validatedRatings($data);
        if ($ratings === null) {
            return $this->fail('Please give a rating from 1 to 5 for every category.');
        }
        if (!$this->commentWithinLimit($data['comment'] ?? null)) {
            return $this->fail('Your comment is a little too long.');
        }

        $this->reviewModel->setBooking_id($bookingId);
        $this->reviewModel->setProperty_id((int) $context['property_id']);
        $this->reviewModel->setUser_id($userId);
        $this->reviewModel->setHost_id((int) $context['host_id']);
        $this->reviewModel->setRating($ratings['rating']);
        $this->reviewModel->setCategories($ratings['categories']);
        $this->reviewModel->setComment($data['comment'] ?? null);

        try {
            $reviewId = $this->reviewModel->create();
        } catch (Throwable $e) {
            // The UNIQUE(booking_id) key turns a double-submit race into a
            // clean "already reviewed" rather than a 500.
            error_log('Review create failed: ' . $e->getMessage());
            return $this->fail('You have already reviewed this stay.', 409);
        }

        echo json_encode([
            'status'    => 'success',
            'message'   => 'Thanks! Your review has been posted.',
            'review_id' => $reviewId,
            'review'    => $this->reviewPayload($ratings, $data['comment'] ?? null),
        ]);
    }

    /**
     * Edit an existing review. Ownership is enforced twice: the row is looked up
     * and its user_id compared to the session, and Review::update() also scopes
     * its WHERE to user_id. Booking/property/host links are immutable.
     */
    public function update()
    {
        $this->requireAuth();
        header('Content-Type: application/json');

        $userId = (int) $_SESSION['user_id'];
        $data = json_decode(file_get_contents('php://input'), true) ?? [];

        $reviewId = filter_var($data['review_id'] ?? null, FILTER_VALIDATE_INT);
        if (!$reviewId) {
            return $this->fail('We could not find that review.');
        }

        $review = $this->reviewModel->getById($reviewId);
        if (!$review || (int) $review['user_id'] !== $userId) {
            // Don't distinguish "missing" from "not yours".
            return $this->fail('We could not find that review.', 404);
        }

        $ratings = $this->validatedRatings($data);
        if ($ratings === null) {
            return $this->fail('Please give a rating from 1 to 5 for every category.');
        }
        if (!$this->commentWithinLimit($data['comment'] ?? null)) {
            return $this->fail('Your comment is a little too long.');
        }

        $this->reviewModel->setId($reviewId);
        $this->reviewModel->setUser_id($userId);
        $this->reviewModel->setRating($ratings['rating']);
        $this->reviewModel->setCategories($ratings['categories']);
        $this->reviewModel->setComment($data['comment'] ?? null);

        try {
            $this->reviewModel->update();
        } catch (Throwable $e) {
            error_log('Review update failed: ' . $e->getMessage());
            return $this->fail('Something went wrong saving your review. Please try again.', 500);
        }

        echo json_encode([
            'status'    => 'success',
            'message'   => 'Your review has been updated.',
            'review_id' => $reviewId,
            'review'    => $this->reviewPayload($ratings, $data['comment'] ?? null),
        ]);
    }

    /**
     * Pull the overall rating + the six categories out of the request and
     * validate each is an integer 1..5. Returns null if anything is missing or
     * out of range, otherwise ['rating' => int, 'categories' => array<string,int>].
     */
    private function validatedRatings(array $data): ?array
    {
        if (!Review::isValidScore($data['rating'] ?? null)) {
            return null;
        }

        $categories = [];
        foreach (array_keys(Review::CATEGORIES) as $key) {
            if (!Review::isValidScore($data[$key] ?? null)) {
                return null;
            }
            $categories[$key] = (int) $data[$key];
        }

        return ['rating' => (int) $data['rating'], 'categories' => $categories];
    }

    /** Optional comment must fit the stored column / UI counter. */
    private function commentWithinLimit($comment): bool
    {
        return $comment === null || mb_strlen(trim((string) $comment)) <= Review::COMMENT_MAX;
    }

    /** Shape the saved review for the client so it can refresh the card in place. */
    private function reviewPayload(array $ratings, $comment): array
    {
        return array_merge(
            ['rating' => $ratings['rating']],
            $ratings['categories'],
            ['comment' => trim((string) ($comment ?? ''))]
        );
    }

    /** Echo a JSON error with an HTTP status and stop. Mirrors the AJAX style. */
    private function fail(string $message, int $code = 400): void
    {
        http_response_code($code);
        echo json_encode(['status' => 'error', 'message' => $message]);
    }
}
?>
