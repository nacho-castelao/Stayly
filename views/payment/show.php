<?php
// Rendered by PaymentController::show() between the layout header and footer.
// $booking is the awaiting_payment row owned by the current user.
$nights = (int) (new DateTimeImmutable($booking['start_date']))
    ->diff(new DateTimeImmutable($booking['end_date']))->days;
?>
<section class="payment-summary">
    <h1>Confirm and pay</h1>

    <ul class="payment-details">
        <li><span>Check-in</span> <strong><?= htmlspecialchars($booking['start_date']) ?></strong></li>
        <li><span>Check-out</span> <strong><?= htmlspecialchars($booking['end_date']) ?></strong></li>
        <li><span>Nights</span> <strong><?= $nights ?></strong></li>
        <li><span>Guests</span> <strong><?= (int) $booking['guests'] ?></strong></li>
        <li><span>Total</span> <strong><?= number_format((float) $booking['total_price'], 2) ?> €</strong></li>
    </ul>

    <!-- Stub checkout: a real provider (Stripe/Redsys) would render its
         payment element here. Submitting confirms the booking server-side. -->
    <form action="<?= DEFAULT_URL ?>public/Payment/process" method="post" class="payment-form">
        <input type="hidden" name="booking_id" value="<?= (int) $booking['id'] ?>">
        <button type="submit" class="reserve-btn">Confirm &amp; Pay</button>
    </form>
</section>

<div id="toast-container"></div>

<!-- Flash toast bridge (same standard snippet as the other pages). -->
<script src="<?= DEFAULT_URL ?>assets/js/toast.js"></script>
<?php if (isset($_SESSION['toast'])): ?>
    <script>
        showToast('<?= $_SESSION['toast']['type'] ?>', '<?= $_SESSION['toast']['message'] ?>');
    </script>
    <?php unset($_SESSION['toast']); ?>
<?php endif; ?>
