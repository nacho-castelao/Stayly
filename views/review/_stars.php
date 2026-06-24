<?php
/**
 * Shared review presentation helpers. Included by the property page and the
 * dashboard bookings list. Guarded so multiple includes in one request are safe.
 */
if (!function_exists('stayly_stars')) {
    /**
     * Render a row of 5 stars with the first $filled filled in. Used for the
     * per-review overall rating (always an integer 1..5).
     */
    function stayly_stars(int $filled, int $total = 5): string
    {
        $filled = max(0, min($total, $filled));
        $path = 'M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z';
        $out = '<span class="stars-row" role="img" aria-label="' . $filled . ' out of ' . $total . ' stars">';
        for ($i = 1; $i <= $total; $i++) {
            $cls = $i <= $filled ? 'star star--on' : 'star star--off';
            $out .= '<svg class="' . $cls . '" viewBox="0 0 24 24" aria-hidden="true"><path d="' . $path . '"/></svg>';
        }
        return $out . '</span>';
    }
}

if (!function_exists('stayly_rating_label')) {
    /** Format an average like 4.8 (or "New" when there are no reviews yet). */
    function stayly_rating_label(?float $average): string
    {
        return $average === null ? 'New' : number_format($average, 1);
    }
}
?>
