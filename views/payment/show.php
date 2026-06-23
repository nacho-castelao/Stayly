<?php
// Rendered by PaymentController::show() between the layout header and footer.
// $booking  — the awaiting_payment row owned by the current user.
// $property — the booked property (may be []), $mainImage — cover image path|null.
$nights = (int) (new DateTimeImmutable($booking['start_date']))
    ->diff(new DateTimeImmutable($booking['end_date']))->days;

$total       = (float) $booking['total_price'];
$perNight    = $nights > 0 ? $total / $nights : $total;
$title       = $property['title'] ?? 'Your stay';
$city        = $property['city'] ?? null;
$guests      = (int) $booking['guests'];

$fmtDate = static fn(string $iso): string =>
    (new DateTimeImmutable($iso))->format('D, M j, Y');
?>
<link rel="stylesheet" href="<?= DEFAULT_URL ?>assets/css/payment.css">

<section class="checkout">
    <a class="checkout__back" href="<?= DEFAULT_URL ?>public/Property/showOne?id=<?= (int) $booking['property_id'] ?>">
        <span aria-hidden="true">&larr;</span> Back to listing
    </a>

    <header class="checkout__head">
        <h1>Confirm and pay</h1>
        <p class="checkout__sub">Review your trip details before completing your booking.</p>
    </header>

    <div class="checkout__grid">
        <!-- LEFT: payment panel (stub checkout) -->
        <div class="checkout__main">
            <div class="pay-card">
                <div class="pay-card__status">
                    <span class="pay-card__badge">Reserved &mdash; awaiting payment</span>
                </div>

                <h2 class="pay-card__title">Payment</h2>

                <!-- Stub checkout: a real provider (Stripe/Redsys) would mount its
                     payment element in place of this notice. -->
                <div class="pay-card__placeholder" aria-hidden="true">
                    <div class="pay-field pay-field--lg"></div>
                    <div class="pay-field-row">
                        <div class="pay-field"></div>
                        <div class="pay-field pay-field--sm"></div>
                    </div>
                    <p class="pay-card__note">Secure card payment — no real charge is taken in this demo.</p>
                </div>

                <form action="<?= DEFAULT_URL ?>public/Payment/process" method="post" class="pay-card__form">
                    <input type="hidden" name="booking_id" value="<?= (int) $booking['id'] ?>">
                    <button type="submit" class="reserve-btn pay-card__submit">
                        Confirm &amp; pay <?= number_format($total, 2) ?> &euro;
                    </button>
                </form>

                <ul class="pay-card__trust">
                    <li><span class="pay-card__tick">&#10003;</span> Free cancellation within 48 hours</li>
                    <li><span class="pay-card__tick">&#10003;</span> Encrypted, secure checkout</li>
                    <li><span class="pay-card__tick">&#10003;</span> You won't be charged until confirmed</li>
                </ul>
            </div>
        </div>

        <!-- RIGHT: trip / price summary -->
        <aside class="checkout__aside">
            <div class="summary-card">
                <div class="summary-card__property">
                    <?php if ($mainImage): ?>
                        <img class="summary-card__img"
                             src="<?= DEFAULT_URL ?>assets/<?= htmlspecialchars($mainImage) ?>"
                             alt="<?= htmlspecialchars($title) ?>">
                    <?php else: ?>
                        <div class="summary-card__img summary-card__img--ph" aria-hidden="true"></div>
                    <?php endif; ?>

                    <div class="summary-card__property-info">
                        <span class="summary-card__name"><?= htmlspecialchars($title) ?></span>
                        <?php if ($city): ?>
                            <span class="summary-card__location"><?= htmlspecialchars($city) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="summary-card__divider"></div>

                <dl class="summary-card__trip">
                    <div class="summary-card__row">
                        <dt>Check-in</dt>
                        <dd><?= htmlspecialchars($fmtDate($booking['start_date'])) ?></dd>
                    </div>
                    <div class="summary-card__row">
                        <dt>Check-out</dt>
                        <dd><?= htmlspecialchars($fmtDate($booking['end_date'])) ?></dd>
                    </div>
                    <div class="summary-card__row">
                        <dt>Guests</dt>
                        <dd><?= $guests ?> <?= $guests === 1 ? 'guest' : 'guests' ?></dd>
                    </div>
                </dl>

                <div class="summary-card__divider"></div>

                <dl class="summary-card__price">
                    <div class="summary-card__row">
                        <dt><?= number_format($perNight, 2) ?> &euro; &times; <?= $nights ?> <?= $nights === 1 ? 'night' : 'nights' ?></dt>
                        <dd><?= number_format($total, 2) ?> &euro;</dd>
                    </div>
                </dl>

                <div class="summary-card__divider"></div>

                <div class="summary-card__total">
                    <span>Total <small>(EUR)</small></span>
                    <span><?= number_format($total, 2) ?> &euro;</span>
                </div>
            </div>
        </aside>
    </div>
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
