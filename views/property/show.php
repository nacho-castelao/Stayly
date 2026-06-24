<?php require_once BASE_PATH . '/views/review/_stars.php'; ?>
<?php if ($prop && $images): ?>
    <div class="property-header">
        <?php while ($img = $images->fetch()): ?>
            <?php if ($img['is_main'] === 1): ?>

                <div class="img-main">
                    <img src="<?= DEFAULT_URL ?>assets/<?= $img['image_url'] ?>" alt="">
                </div>

                <?php continue; ?>
            <?php endif; ?>

            <img src="<?= DEFAULT_URL ?>assets/<?= $img['image_url'] ?>" alt="">

        <?php endwhile; ?>
        <!-- CURRENTLY WORKING ON THIS BUTTON -->
        <button class="fav-icon <?= $isSaved ? 'active' : '' ?>" data-id="<?= $prop['id'] ?>">
            <img src="<?= DEFAULT_URL ?>assets/img/favButton1.svg" alt="Fav icon">
        </button>
    </div>

    <div class="property-main">
        <div class="specs">
            <span><?= htmlspecialchars($prop['title']); ?></span>

            <div class="stars">
                <?php if ($reviewStats['total'] > 0): ?>
                    <a href="#reviews" class="rating-summary" title="See all reviews">
                        <svg class="star star--on rating-summary__icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                        </svg>
                        <span class="rating-summary__value"><?= stayly_rating_label($reviewStats['average']) ?></span>
                        <span class="rating-summary__count">·
                            <?= (int) $reviewStats['total'] ?> <?= $reviewStats['total'] === 1 ? 'review' : 'reviews' ?>
                        </span>
                    </a>
                <?php else: ?>
                    <span class="rating-summary rating-summary--new">
                        <svg class="star star--on rating-summary__icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                        </svg>
                        <span class="rating-summary__value">New</span>
                    </span>
                <?php endif; ?>
            </div>

            <div class="location">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <rect width="28" height="28" fill="url(#pattern0_124_229)" />
                    <defs>
                        <pattern id="pattern0_124_229" patternContentUnits="objectBoundingBox" width="1" height="1">
                            <use xlink:href="#image0_124_229" transform="scale(0.02)" />
                        </pattern>
                        <image id="image0_124_229" width="50" height="50" preserveAspectRatio="none" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAAD1UlEQVR4nNWaa4hVVRTHfz6qQREaZ3wSKvRwIhXziT3ATw4ODBFJEUj0cFDGd1nOBKIownwqJkgZ9GNMHyIIoigtKjKZcZrRxvGOMio+0JGsmNLUxvTKgv+FxeWOd+49j3vvHw6cu8/Za6//OXuv9d/rXCgOjADeBfqB34HNlCg+AJJpxzZKDC/I8RtANbAS+F9tjZQIJgFX5PQ61/6KI/M+RQ5bF1/J2W/02+N14I6uN1DEeEdO2uKePMQ9bzgy71GEmAXcBO4CtVnufVNk7N71FBHKgG495Y+H2ectR2YtRYK9IpEAxuTQr05E7KinwFguRwaBhXn0r3Nk1lAEoXZLADsbZcPIrKaAofYnYFRAe5tky9bNa0SAqcBsYD7wFFCh9rc18J/AIyGNtUU2LXG+FMZ0qVdC+yuDXkpqOv2n8xWEi62yeyZfA9OBFudg6jAyXcCPQBtwwV3bT/h4VrbP59pxJLABuO4c/FmLbsYQfSqBJSGsCzJImAH5sJscE9lnjsAhYBHxYxLwhfOjFXggFwOpqDOoqJEu8uLACuCq/BjQW8kZv8jAKWAC8WK8nnzqLRwEpuVrzJz/TYZ676NYw0Y1cNFtvrZqrQZCOfBrTGTGAM3K4DbeYeCJMAcwMh2OzBTCxzNAn8a4qbcQdtSLlEwZ0OQ2UzaV5xIxTH4c1YDHgYkB7dk07XWRcXuuYTUomS4N3hOQTI2TNPMoAB4GjoQwzSzRmY1LFBC7XIzvkVP5oF82LG/EjhotTpPTpwOS6VT/mcSMR518b5BAPKbfJ/Ig06a+C4gRY12m/9xpLx/NciXTrX5PEiNaXTVkXNq18S6hDZeMPYh/1Cc2LbdZA/4NVKVdMx20wyW14ZJ5zIXfWLAUuC0N9GKGUPyl20tvd9MlkYWMFbGT6h85LEdcHmJXViVn7dofwLIMQvOkChSZkJI8VlWMFA+5qPJtmoh7Gbima50Ztr2VLjAkMqjm551Mr4iayD4Ndk6OITJNTmp/cp8yqBea/s3YmmpXe3PUJOrcE3tabRZZvnciz3+oGQo+NKfeTKPbuk6NkoQVGG5psFRVb75KMEnJiufyFJpnFTjsfBURYqLbZn6kNvvG96/aOvLcO3uhacenURYzRgM/uNKPZfI9bnA7fzCAfUua36nEFMROVnzops4CV0W5FfU0CBOvukXc6HKHTbPFlAjmuJJoQmSSilBx17PyRrkTe/5oiXP/HBQjXVk06fJGJB9SosTONBJ9+mhTUqhNk91fa5qVFB533xnuSjsFrq0WAu1O62T7F0JR44DIhFogjhv3APByS5e0g7UeAAAAAElFTkSuQmCC" />
                    </defs>
                </svg>

                <span><?= htmlspecialchars($prop['city']) ?></span>
            </div>

            <div class="rooms">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <rect width="28" height="28" fill="url(#pattern0_114_702)" />
                    <defs>
                        <pattern id="pattern0_114_702" patternContentUnits="objectBoundingBox" width="1" height="1">
                            <use xlink:href="#image0_114_702" transform="scale(0.02)" />
                        </pattern>
                        <image id="image0_114_702" width="50" height="50" preserveAspectRatio="none" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAADUUlEQVR4nO2aTWjUQBTHfyvqVitY67p+9KTYrRfpYY+KIHjwoiDai4eCHopg/QBRD0XwE7x6lEJFL0IVtAp6KKJIpSp+ICii2Fq/QKtSrahtrUYCLzCOSbNJpskuu38IbDLJzPvP/OfNzHsLFZQPGoAjwADQDxwEllIimAO0AD3AH8Byue4Bu4AsRYYqoAm4BIwpBn8FOpT7Dnnm3I8CF4GNQDpJAnngBPBRMW4c6AaagWp5zylzSK8DOjXSX4AzwBogFZfuba2/0OTyBNgPzHf5RiWiotZDhq+B49JWLLp/IyPS6PO9FxEVy3w6KBvW+LQigdGIEiiESBjJRqpkJsERlEjkzryhDWsvsB3IEA1hiajIiC29mo3XcYFTeAioxxxMEFFRLzZ61mu6wcTqtUI2uAg4ALwEbgWo9xrQBxwOqQDLBJEpMtn0hc3te7/n+tZlXhxE6qT3X2lbjc4IRE4Bw8r9CHBOPNY000RWAF3iip33ngP7lMUqLBHErW8GrmptDAJ7TRIZVnrrLLDaxYdHIaJiIbAHeCTlv0wSccomWlNMEQlqU+EFERutEKFChPImUgPsKGUiK4HTwA+l/H6pEKmR06Lj4y05NfbI8xmlQuSn8vsdcAxYEtKYRIiMSdm4hH3WA1N9jChKIltkX2Vv2YOiqIiYOMnZv3OlQiTjcbZWrzvAzmIkUgVskm2+Gu0YAtqBVXK1yzOd2IYJQqZWHETCxJ+8QjxDHiEea7KI2BHBo5IqUHv1NtAaMHTkJcN+OcfnTBPJiqbvejTYYNAx9HnMq2xYIklGzfMi2UEfyfoScctjdMmEtgnGhbQ4ggvafNLzLv8g5TKcTsh0LsmjFtjmkQn7j8hDbSjtbNJaiWEljZTI+bwm9QduEnde1ufFW8ldJJHzmy276seKPb9lvjQVst9bIAmWAZdAXBzpsTxwEviutP9eMlmLw1SohkbVwNkzIWpy/tiOpFnkoodSW0w6mjqR2AftfOKMUlg0SE9/1jyTPSLLmURMF312ax7E6blZBuqoJmbkpDc/ufRmY4GjOmJgVI3BDkJvddnG3JTgtH2ivCIexyl7CuyWOEBRIi8j8s1lkbUdxuU4/xhgag1olYiLvcFsE7deQVngL8XqU85z1uroAAAAAElFTkSuQmCC" />
                    </defs>
                </svg>

                <span><?= $prop['rooms'] ?> rooms</span>
            </div>

            <div class="bathrooms">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <rect width="28" height="28" fill="url(#pattern0_114_703)" />
                    <defs>
                        <pattern id="pattern0_114_703" patternContentUnits="objectBoundingBox" width="1" height="1">
                            <use xlink:href="#image0_114_703" transform="scale(0.02)" />
                        </pattern>
                        <image id="image0_114_703" width="50" height="50" preserveAspectRatio="none" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAADsElEQVR4nO1aW0tVQRT+6tjxIdJ6zMAy6K0sMwQxuhh0sejiQz+itKfqzcgudqXoYmY912P1UD9AKrOyiKDUMELUNEIKiyiVdqz4NozbfZnZt1NwPlh4ZvZa39rfnjUzZ88R8EYhgEYAXQC+054AaACQRbxILNciAK8AWB72EkBJTCISy1WoEL8BsAvAPNpuAD289iKGkUk01wEGvwVQ7HK9WEmwP6KQRHM9ZaA8HS/soY/UcRQkmusbA2V4vVBEn3FT8jRzjWuQF8ckJNFcXQyUyeaFevp0mpKnmauBgT0eE3A+gD767DMlTzNXlmu3BPdyshXR6hXibgBzIgpJPFeJksDNhHhhRBGp5cpy7e7k6iL2mEMcdSRymev/QRmAdgCDAKZ8htq2KfpeZ6wpqgE8APBVI9cX+kqML7Yom1MYkzV+s4GIvQAmQ+SZZKwryhQRdwBUan5By9L3riJmsUbcEmUjPAugVCOmFMC5oDztioiwuEeONg3fS/S9FSLPbcYKxwwM8uJqhEclOQYC/DIARulbESJPBWNHyTUN9sSO8m6RVRYAP6ynX3+EXP3kEK5psCdSVOjwXKHP6Qh5zpDjctANdAB4rkHY4XhHCBIyC8AQfaoQHlXkGCKn5w3ojpBpXLUyj+QGlnF/aNLI1QzgE4CljB0gV3UuhJzn9Ytsn2D7qkauVvpKDMhhkTN1Ie95vYbtXrY3auSqpe87ttey/UEtrzSErOG1EQCzAazyW0ZdkGFpScxKcgyzLUt/akJOOcropEFZ2WhzlJddbi1pCnGWUY/XXqBRXn0e5Za4kHL2fwZQwNKwy0ynrNzKq9zRXpGGkGb233CsVjM2NIPyOs72TbaPpiHkEfu3O8pqHcxRy1jhEOxg+2EaQrrZv5Ni5PNHrjymyCjlVEdOizkSF3JEuWabvDqERasL399vB/Z6vE3ZaEyE1ChxwuWEHB5cADCmxGyKIGSDwjPG3b1AnXxOQ8AXSbeYYz43sNxwE/RChiueRc5pT6yFdTviIcSvb4SxLfaT8cBh+l9DdLSS65Cfk6kQ099C4rTGXAhZwCOdiRgETAC4T87Uhejms0KuooHEJn1x5LPyQhzICwmJvJAg5IWERF5IEH45zoLtc123vp/4h4U8Y+BBAHP51/Lok3/BiIphl9cBy9GW41Fj1Clktv2mOfvkHSYq7KMiP5Pjo1AQMfK0fwB4DWArb1r65Nct+Y+FOEQIpExFjIyMvA7IZzH5LCMhIgJ/8vgDVtyqHx0nXQYAAAAASUVORK5CYII=" />
                    </defs>
                </svg>

                <span><?= $prop['bathrooms'] ?> bathrooms</span>
            </div>

            <div class="metres">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <rect width="28" height="28" fill="url(#pattern0_114_704)" />
                    <defs>
                        <pattern id="pattern0_114_704" patternContentUnits="objectBoundingBox" width="1" height="1">
                            <use xlink:href="#image0_114_704" transform="scale(0.02)" />
                        </pattern>
                        <image id="image0_114_704" width="50" height="50" preserveAspectRatio="none" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAbElEQVR4nO3PsQ2AUAzE0Ow/Foi5TEv3ocIIP+m6FPFMkqeOmUG+/U4IH9nS7cOXUIgMhchQiAyFyFCIDIXIUIgMhchQiAyFyFCIDIXIUIgMhchQiAyFyFCIDIXI8NsQ5FvaBU+y2LbOSDIXJ0y7PeGBCf+kAAAAAElFTkSuQmCC" />
                    </defs>
                </svg>

                <span><?= $prop['size_m2'] ?> m<sup>2</sup></span>
            </div>
        </div>

        <div class="cta">
            <span class="price_per_night"> <?= $prop['price_per_night'] ?> € / night</span>

            <form action="<?= DEFAULT_URL ?>public/Booking/create" method="post" id="booking-form" class="booking-form">
                <!-- Trigger field. Clicking it expands the calendar popover
                     (assets/js/calendar.js); the picked range is mirrored into
                     the labels here and the hidden inputs below (ISO YYYY-MM-DD). -->
                <div class="date-field-wrap">
                    <button type="button" class="date-field" id="date-field"
                        aria-haspopup="dialog" aria-expanded="false" aria-controls="cal-popover">
                        <span class="date-field-col">
                            <span class="text-caption">Check-in</span>
                            <span class="date-field-val is-empty" id="checkin-label">Add date</span>
                        </span>
                        <span class="date-field-divider"></span>
                        <span class="date-field-col">
                            <span class="text-caption">Check-out</span>
                            <span class="date-field-val is-empty" id="checkout-label">Add date</span>
                        </span>
                    </button>

                    <div class="cal-popover" id="cal-popover" role="dialog" aria-label="Choose dates" hidden>
                        <div id="stay-calendar" class="stay-calendar"></div>
                    </div>
                </div>

                <input type="hidden" name="property_id" value="<?= $prop['id'] ?>">
                <input type="hidden" name="start_date" id="start_date">
                <input type="hidden" name="end_date" id="end_date">

                <div class="guests">
                    <span class="text-caption">Guests</span>

                    <select name="guests" id="guests">
                        <?php for ($i = 1; $i <= $prop['max_guests']; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?> guests</option>
                        <?php endfor; ?>
                    </select>
                </div>

                <button type="submit" class="reserve-btn" id="reserve-btn" disabled>Reserve</button>
            </form>
        </div>

        <div class="description">
            <?= htmlspecialchars($prop['description']) ?>
        </div>

        <div class="host-info">
            <div class="host-img">
                <?php $hostAvatar = !empty($host['avatar_url']) ? 'img/users/' . $host['avatar_url'] : 'img/User.svg'; ?>
                <img src="<?= DEFAULT_URL ?>assets/<?= htmlspecialchars($hostAvatar) ?>" alt="avatar">
            </div>

            <div class="host-details">
                <div>
                    <span class="host-name"><?= htmlspecialchars($host['name']) ?></span>
                    <span class="host-sub">Member since <?= substr($host['created_at'], 0, 4) ?></span>
                    <?php if ($hostStats['total'] > 0): ?>
                        <span class="host-rating">
                            <svg class="star star--on" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                            </svg>
                            <?= stayly_rating_label($hostStats['average']) ?> ·
                            <?= (int) $hostStats['total'] ?> <?= $hostStats['total'] === 1 ? 'review' : 'reviews' ?>
                        </span>
                    <?php endif; ?>
                </div>

                <span class="host-desc text-body-small"><?= htmlspecialchars($host['biography']) ?></span>

                <span class="host-lang">
                    Languages:
                    <?= htmlspecialchars($languagesPacked) ?>
                </span>
            </div>
        </div>

        <section class="amenities">
            <span class="amenities-title">Amenities</span>

            <div>
                <?php while ($am = $amenities->fetch()): ?>
                    <div>
                        <img src="../../assets/img/amenities/<?= $am['icon'] ?>.svg" alt="Amenity icon">
                        <span><?= $am['name'] ?></span>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>

        <section class="reviews" id="reviews">
            <?php if ($reviewStats['total'] > 0): ?>
                <div class="reviews__head">
                    <h2 class="reviews__title">
                        <svg class="star star--on" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                        </svg>
                        <?= stayly_rating_label($reviewStats['average']) ?>
                        <span class="reviews__count">·
                            <?= (int) $reviewStats['total'] ?> <?= $reviewStats['total'] === 1 ? 'review' : 'reviews' ?>
                        </span>
                    </h2>
                </div>

                <div class="reviews__overview">
                    <!-- Star distribution (5 → 1) -->
                    <ul class="rating-bars">
                        <?php foreach ([5, 4, 3, 2, 1] as $star):
                            $count = $reviewStats['distribution'][$star];
                            $pct = $reviewStats['total'] > 0 ? round($count / $reviewStats['total'] * 100) : 0;
                        ?>
                            <li class="rating-bar">
                                <span class="rating-bar__label"><?= $star ?></span>
                                <span class="rating-bar__track">
                                    <span class="rating-bar__fill" style="width: <?= $pct ?>%"></span>
                                </span>
                                <span class="rating-bar__count"><?= $count ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <!-- Per-category averages -->
                    <ul class="rating-categories">
                        <?php foreach (Review::CATEGORIES as $key => $label):
                            $avg = $reviewStats['categories'][$key];
                        ?>
                            <li class="rating-category">
                                <span class="rating-category__label"><?= htmlspecialchars($label) ?></span>
                                <span class="rating-category__track">
                                    <span class="rating-category__fill" style="width: <?= ($avg ?? 0) / 5 * 100 ?>%"></span>
                                </span>
                                <span class="rating-category__value"><?= $avg !== null ? number_format($avg, 1) : '—' ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <ul class="review-list">
                    <?php foreach ($reviews as $rev):
                        $avatar = !empty($rev['guest_avatar'])
                            ? 'img/users/' . $rev['guest_avatar']
                            : 'img/User.svg';
                        $reviewDate = (new DateTimeImmutable($rev['created_at']))->format('F Y');
                    ?>
                        <li class="review-card">
                            <div class="review-card__head">
                                <img class="review-card__avatar"
                                    src="<?= DEFAULT_URL ?>assets/<?= htmlspecialchars($avatar) ?>"
                                    alt="<?= htmlspecialchars($rev['guest_name']) ?>">
                                <div class="review-card__meta">
                                    <span class="review-card__name"><?= htmlspecialchars($rev['guest_name']) ?></span>
                                    <span class="review-card__date"><?= htmlspecialchars($reviewDate) ?></span>
                                </div>
                            </div>

                            <div class="review-card__rating">
                                <?= stayly_stars((int) $rev['rating']) ?>
                            </div>

                            <?php if (!empty($rev['comment'])): ?>
                                <p class="review-card__comment"><?= nl2br(htmlspecialchars($rev['comment'])) ?></p>
                            <?php endif; ?>

                            <ul class="review-card__categories">
                                <?php foreach (Review::CATEGORIES as $key => $label): ?>
                                    <li>
                                        <span class="review-card__cat-label"><?= htmlspecialchars($label) ?></span>
                                        <span class="review-card__cat-value"><?= (int) $rev[$key] ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="reviews__head">
                    <h2 class="reviews__title">
                        <svg class="star star--on" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" />
                        </svg>
                        No reviews yet
                    </h2>
                </div>
                <p class="reviews__empty">This place is waiting for its first review. Be the first to stay and share your experience.</p>
            <?php endif; ?>
        </section>

        <section class="map-wrapper">
            <div class="map-title">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <rect width="28" height="28" fill="url(#pattern0_135_476)" />
                    <defs>
                        <pattern id="pattern0_135_476" patternContentUnits="objectBoundingBox" width="1" height="1">
                            <use xlink:href="#image0_135_476" transform="scale(0.0357143)" />
                        </pattern>
                        <image id="image0_135_476" width="28" height="28" preserveAspectRatio="none" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAYAAAByDd+UAAAACXBIWXMAAAsTAAALEwEAmpwYAAABf0lEQVR4nO3WMUgWcRzG8U9JRmoQlEMSvKLQGEG1SQ5C2OpgENTo0iBODQ41Nr1CW4uD0BJUg5NYVIO42KJWU0FEEKIkguam/F9+B8fx3uvrEDXcA/+Du/s//+/vueUe/oGWMI2zbe6/jpd4hZttejoxhYV0c4B1bB4BTqB57OIrvmALyxjDySaeE7iLb7ESq3GpYQjv4pDHOBemK3iBfTxDH+qxujGJ7zHEJM6EbwQf8QMTGCgCM43G1Cnxm0g0g4u5PRkw0yncwyp+4T1+42FugFoZMFOa9mcBVAbMf8KpSHW+8O5IYEr6ucnzVsBWvgqoAtYrYEEVUAWsV8D/CnjrGMbTUaBeo+sYvuEM+CRqxHwUpTJjAj2Iv/kaVrCBR7jQwncJs9gLVkO9OfAibuSMnVGCEugT7qMjfEMx6B/M4XLO1xNlbDdK2GCT1I3+MhOb0uHbAUo1crykCiZdxfNodin5TpSwt7hW4mkK/oA7LUBF9eNp1M3bbXr+rg4BAlG1DG+LQW4AAAAASUVORK5CYII=" />
                    </defs>
                </svg>

                <span>Location</span>
            </div>

            <div id="map" data-lat="<?= $prop['latitude'] ?>" data-lng="<?= $prop['longitude'] ?>"></div>

            <span class="location-desc text-body "><?= htmlspecialchars($prop['location_description']) ?></span>
        </section>
    </div>

    <div class="property-footer">

    </div>

    <div id="toast-container">

    </div>

    <!-- Flash toast bridge: surfaces $_SESSION['toast'] set by the booking
         flow (BookingController), matching the other pages. toast.js is loaded
         as a classic script so showToast is global for the inline call below. -->
    <script src="<?= DEFAULT_URL ?>assets/js/toast.js"></script>
    <?php if (isset($_SESSION['toast'])): ?>
        <script>
            showToast('<?= $_SESSION['toast']['type'] ?>', '<?= $_SESSION['toast']['message'] ?>');
        </script>
        <?php unset($_SESSION['toast']); ?>
    <?php endif; ?>

    <script>
        // Mount the date-range picker as a popover behind the date field.
        // calendar.js is loaded with `defer` in the layout head, so
        // window.Calendar is ready before DOMContentLoaded fires.
        document.addEventListener('DOMContentLoaded', function () {
            const mount = document.getElementById('stay-calendar');
            if (!mount || typeof Calendar === 'undefined') return;

            const wrap = document.querySelector('.date-field-wrap');
            const field = document.getElementById('date-field');
            const popover = document.getElementById('cal-popover');
            const checkinLabel = document.getElementById('checkin-label');
            const checkoutLabel = document.getElementById('checkout-label');
            const startInput = document.getElementById('start_date');
            const endInput = document.getElementById('end_date');
            const reserveBtn = document.getElementById('reserve-btn');

            // Local midnight today — used to block past dates.
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            // Serialize to YYYY-MM-DD for the server (NOT toLocaleDateString).
            const toISO = (d) =>
                d.getFullYear() + '-' +
                String(d.getMonth() + 1).padStart(2, '0') + '-' +
                String(d.getDate()).padStart(2, '0');

            const toLabel = (d) => d.toLocaleDateString(undefined, { month: 'short', day: 'numeric' });

            function setLabel(el, date) {
                el.textContent = date ? toLabel(date) : 'Add date';
                el.classList.toggle('is-empty', !date);
            }

            function open() { popover.hidden = false; field.setAttribute('aria-expanded', 'true'); }
            function close() { popover.hidden = true; field.setAttribute('aria-expanded', 'false'); }

            // Unavailable dates for this property, fetched below. Booked ranges
            // are half-open [start, end) so a check-out day stays selectable as
            // someone else's check-in. This only greys out cells for UX — the
            // server re-validates on submit, so a failed/slow fetch is harmless.
            let bookedRanges = [];          // [{ start: Date, end: Date }]
            const blockedDays = new Set();  // 'YYYY-MM-DD'

            function isUnavailable(date) {
                if (blockedDays.has(toISO(date))) return true;
                for (const r of bookedRanges) {
                    if (date >= r.start && date < r.end) return true;
                }
                return false;
            }

            const cal = new Calendar(mount, {
                initialDate: today,
                isDisabled: (date) => date < today || isUnavailable(date),
                onSelect: function (range) {
                    startInput.value = range.start ? toISO(range.start) : '';
                    endInput.value = range.end ? toISO(range.end) : '';
                    setLabel(checkinLabel, range.start);
                    setLabel(checkoutLabel, range.end);
                    // Require a full check-in + check-out range before reserving.
                    reserveBtn.disabled = !(range.start && range.end);
                    // Collapse once a full range is chosen.
                    if (range.start && range.end) close();
                },
            });

            field.addEventListener('click', function () {
                popover.hidden ? open() : close();
            });

            // Pull booked/blocked dates and repaint so they grey out. The
            // calendar is already usable (past dates blocked); this just adds
            // the property-specific unavailability once it arrives.
            fetch('<?= DEFAULT_URL ?>public/Property/availability?id=<?= (int) $prop['id'] ?>')
                .then(function (r) { return r.ok ? r.json() : null; })
                .then(function (data) {
                    if (!data) return;
                    bookedRanges = (data.booked || []).map(function (b) {
                        return {
                            start: new Date(b.start_date + 'T00:00:00'),
                            end: new Date(b.end_date + 'T00:00:00'),
                        };
                    });
                    (data.blocked || []).forEach(function (d) { blockedDays.add(d); });
                    cal.render();
                })
                .catch(function () { /* leave calendar open; server still validates */ });

            // Close when clicking outside the field/popover, or on Escape.
            document.addEventListener('click', function (e) {
                if (!wrap.contains(e.target)) close();
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') close();
            });
        });
    </script>
<?php else: ?>
    <h1>Property not found</h1>
    <p>The property you are looking for does not exist.</p>
<?php endif; ?>