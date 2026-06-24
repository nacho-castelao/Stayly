<?php
/**
 * Review form modal — one instance, reused for both "Leave review" and "Edit
 * review". review.js opens it, fills it from the clicked booking card (create
 * vs edit), drives the star/category selectors + character counter, and submits
 * to Review/create or Review/update over AJAX. Reuses the shared .modal / .hidden
 * overlay so the backdrop, centering and zoom-in animation match the rest of the app.
 *
 * Expects models/Review.php to be loaded (for Review::CATEGORIES / COMMENT_MAX).
 */
$starPath = 'M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z';

/** Render one 1–5 star selector bound to $field (overall rating or a category). */
$renderStarInput = static function (string $field) use ($starPath): void { ?>
    <div class="star-input" data-field="<?= htmlspecialchars($field) ?>" role="radiogroup"
        aria-label="Rating for <?= htmlspecialchars($field) ?>">
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <button type="button" class="star-input__star" data-value="<?= $i ?>"
                role="radio" aria-checked="false" aria-label="<?= $i ?> out of 5">
                <svg class="star" viewBox="0 0 24 24" aria-hidden="true"><path d="<?= $starPath ?>" /></svg>
            </button>
        <?php endfor; ?>
    </div>
<?php };
?>

<div class="modal hidden review-modal" id="reviewModal" role="dialog" aria-modal="true" aria-labelledby="reviewModalTitle">
    <div class="modal-box review-modal__box">
        <button type="button" class="modal-close" id="reviewModalClose" aria-label="Close">&times;</button>

        <h3 id="reviewModalTitle" class="review-modal__title">Leave a review</h3>
        <p class="review-modal__subtitle" id="reviewModalSubtitle">Share your experience to help other guests.</p>

        <form id="reviewForm" class="review-form" novalidate
            data-create-url="<?= DEFAULT_URL ?>public/Review/create"
            data-update-url="<?= DEFAULT_URL ?>public/Review/update">
            <input type="hidden" name="mode" id="reviewMode" value="create">
            <input type="hidden" name="booking_id" id="reviewBookingId" value="">
            <input type="hidden" name="review_id" id="reviewReviewId" value="">

            <!-- Overall rating -->
            <div class="review-form__overall">
                <span class="review-form__label">Overall rating</span>
                <?php $renderStarInput('rating'); ?>
            </div>

            <!-- Category ratings -->
            <div class="review-form__categories">
                <?php foreach (Review::CATEGORIES as $key => $label): ?>
                    <div class="review-form__category">
                        <span class="review-form__cat-label"><?= htmlspecialchars($label) ?></span>
                        <?php $renderStarInput($key); ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Written comment -->
            <div class="review-form__comment">
                <label class="review-form__label" for="reviewComment">Your review <span class="review-form__optional">(optional)</span></label>
                <textarea id="reviewComment" name="comment" class="review-form__textarea"
                    maxlength="<?= Review::COMMENT_MAX ?>" rows="4"
                    placeholder="What did you love about this stay? What could be better?"></textarea>
                <span class="review-form__counter"><span id="reviewCounter">0</span>/<?= Review::COMMENT_MAX ?></span>
            </div>

            <p class="review-form__error" id="reviewError" role="alert" hidden></p>

            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" id="reviewCancel">Cancel</button>
                <button type="submit" class="btn btn-primary review-form__submit" id="reviewSubmit">
                    <span class="review-form__submit-label">Post review</span>
                    <span class="review-form__spinner" aria-hidden="true"></span>
                </button>
            </div>
        </form>
    </div>
</div>
