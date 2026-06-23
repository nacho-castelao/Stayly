<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>User Settings</title>
    <link rel="stylesheet" href="<?= DEFAULT_URL ?>assets/css/style.css" />
    <link rel="stylesheet" href="<?= DEFAULT_URL ?>assets/css/dashboard.css" />
    <script src="<?= DEFAULT_URL ?>assets/js/main.js" defer></script>
    <script src="<?= DEFAULT_URL ?>assets/js/modal.js" defer></script>
</head>

<body>
    <div class="dashboard">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="logo">
                <img src="<?= DEFAULT_URL ?>/assets/img/LogoLittle.svg" alt="Stayly Logo">
            </div>

            <nav class="menu">
                <div>
                    <a href="<?= DEFAULT_URL ?>public/User/showDashboard?page=dashboard" class="menu-item <?= $page === 'dashboard' ? 'active' : '' ?>">
                        <img src="<?= DEFAULT_URL ?>assets/img/dashboard/layout-dashboard.svg" class="icon" />
                        Dashboard
                    </a>

                    <a href="<?= DEFAULT_URL ?>public/User/showDashboard?page=bookings" class="menu-item <?= $page === 'bookings' ? 'active' : '' ?>">
                        <img src="<?= DEFAULT_URL ?>assets/img/dashboard/calendar-check-dashboard.svg" class="icon" />
                        My Bookings
                    </a>

                    <a href="<?= DEFAULT_URL ?>public/User/showDashboard?page=wishlist" class="menu-item <?= $page === 'wishlist' ? 'active' : '' ?>">
                        <img src="<?= DEFAULT_URL ?>assets/img/dashboard/heart-dashboard.svg" class="icon" />
                        Wishlist
                    </a>

                    <a href="<?= DEFAULT_URL ?>public/User/showDashboard?page=messages" class="menu-item <?= $page === 'messages' ? 'active' : '' ?>">
                        <img src="<?= DEFAULT_URL ?>assets/img/dashboard/message-square-dashboard.svg" class="icon" />
                        Messages
                    </a>

                    <a href="<?= DEFAULT_URL ?>public/User/showDashboard?page=payments" class="menu-item <?= $page === 'payments' ? 'active' : '' ?>">
                        <img src="<?= DEFAULT_URL ?>assets/img/dashboard/credit-card-dashboard.svg" class="icon" />
                        Payments
                    </a>

                    <a href="<?= DEFAULT_URL ?>public/User/showDashboard?page=settings" class="menu-item <?= $page === 'settings' ? 'active' : '' ?>">
                        <img src="<?= DEFAULT_URL ?>assets/img/dashboard/settings-dashboard.svg" class="icon" />
                        Profile Settings
                    </a>
                </div>

                <div class="menu-bottom">
                    <a class="menu-item">
                        <img src="<?= DEFAULT_URL ?>assets/img/dashboard/info-dashboard.svg" class="icon" />
                        Help
                    </a>

                    <a href="<?= DEFAULT_URL ?>public/User/logout" class="menu-item">
                        <img src="<?= DEFAULT_URL ?>assets/img/dashboard/log-out-dashboard.svg" class="icon" />
                        Log Out
                    </a>
                </div>
            </nav>
        </aside>

        <header class="topbar">
            <h3>Dashboard</h3>

            <div class="user">
                <img src="<?= DEFAULT_URL ?>assets/img/users/<?= $user['avatar_url'] ?>" class="avatar" />

                <button class="btn--disabled">
                    <img src="<?= DEFAULT_URL ?>assets/img/dashboard/chevron-down-dashboard.svg" class="chevron" />

                </button>
            </div>
        </header>

        <!-- MAIN -->
        <?php switch ($page):
            case 'dashboard':
        ?>
                <main class="main">

                    <!-- TOP BAR -->


                    <!-- CONTENT -->
                    <section class="content">
                        <div class="welcome">
                            <h2>Hi <?= $user['name'] ?></h2>
                            <p>Here’s your activity review</p>
                        </div>

                        <!-- CARDS -->
                        <div class="cards">

                            <div class="card">
                                <img src="<?= DEFAULT_URL ?>assets/img/dashboard/calendar-days-dashboard.svg" class="card-icon" />
                                <h2><?= $bookings ?></h2>
                                <p>Upcoming bookings</p>
                            </div>

                            <div class="card">
                                <img src="<?= DEFAULT_URL ?>assets/img/dashboard/heart2-dashboard.svg" class="card-icon" />
                                <h2><?= $wishlist ?></h2>
                                <p>Saved properties</p>
                            </div>

                            <div class="card">
                                <img src="<?= DEFAULT_URL ?>assets/img/dashboard/message-square2-dashboard.svg" class="card-icon" />
                                <h2></h2>
                                <p>New messages</p>
                            </div>

                            <div class="card">
                                <img src="<?= DEFAULT_URL ?>assets/img/dashboard/credit-card2-dashboard.svg" class="card-icon" />
                                <h2></h2>
                                <p>Payments pending</p>
                            </div>

                            <div class="card">
                                <img src="<?= DEFAULT_URL ?>assets/img/dashboard/flag-dashboard.svg" class="card-icon" />
                                <h2></h2>
                                <p>Trips completed</p>
                            </div>

                            <div class="card">
                                <img src="<?= DEFAULT_URL ?>assets/img/dashboard/gauge-dashboard.svg" class="card-icon" />
                                <h2></h2>
                                <p>Profile completion</p>
                            </div>

                        </div>
                    </section>
                </main>
            <?php
                break;

            case 'bookings':
            ?>
                <?php if (empty($bookingsList)): ?>
                    <main class="main empty-state">
                        <div class="empty-state__content">
                            <h3 class="empty-state__title">No upcoming bookings</h3>
                            <p class="empty-state__subtitle">You don't have any bookings yet.</p>
                            <a href="<?= DEFAULT_URL ?>public/Home/index" class="empty-state__btn">Explore stays</a>
                        </div>
                    </main>
                <?php else: ?>
                    <main class="main">
                        <section class="bookings">
                            <div class="bookings__head">
                                <h2 class="bookings__title">My Bookings</h2>
                                <p class="bookings__subtitle"><?= count($bookingsList) ?> <?= count($bookingsList) === 1 ? 'reservation' : 'reservations' ?></p>
                            </div>

                            <ul class="booking-list">
                                <?php foreach ($bookingsList as $b):
                                    $nights = (int) (new DateTimeImmutable($b['start_date']))
                                        ->diff(new DateTimeImmutable($b['end_date']))->days;
                                    $fmt = static fn(string $iso): string =>
                                        (new DateTimeImmutable($iso))->format('M j, Y');
                                    $status = $b['status'];
                                    $statusLabel = ucwords(str_replace('_', ' ', $status));
                                ?>
                                    <li class="booking-item">
                                        <a class="booking-item__media" href="<?= DEFAULT_URL ?>public/Property/showOne?id=<?= (int) $b['property_id'] ?>">
                                            <?php if (!empty($b['property_image'])): ?>
                                                <img src="<?= DEFAULT_URL ?>assets/<?= htmlspecialchars($b['property_image']) ?>" alt="<?= htmlspecialchars($b['property_title']) ?>">
                                            <?php else: ?>
                                                <span class="booking-item__media--ph" aria-hidden="true"></span>
                                            <?php endif; ?>
                                        </a>

                                        <div class="booking-item__body">
                                            <div class="booking-item__top">
                                                <div>
                                                    <h3 class="booking-item__name"><?= htmlspecialchars($b['property_title']) ?></h3>
                                                    <?php if (!empty($b['property_city'])): ?>
                                                        <p class="booking-item__location"><?= htmlspecialchars($b['property_city']) ?></p>
                                                    <?php endif; ?>
                                                </div>
                                                <span class="booking-status booking-status--<?= htmlspecialchars($status) ?>"><?= htmlspecialchars($statusLabel) ?></span>
                                            </div>

                                            <div class="booking-item__meta">
                                                <span class="booking-item__dates">
                                                    <?= htmlspecialchars($fmt($b['start_date'])) ?> &ndash; <?= htmlspecialchars($fmt($b['end_date'])) ?>
                                                    <small>· <?= $nights ?> <?= $nights === 1 ? 'night' : 'nights' ?></small>
                                                </span>
                                                <span class="booking-item__guests"><?= (int) $b['guests'] ?> <?= (int) $b['guests'] === 1 ? 'guest' : 'guests' ?></span>
                                            </div>
                                        </div>

                                        <div class="booking-item__aside">
                                            <span class="booking-item__price"><?= number_format((float) $b['total_price'], 2) ?> &euro;</span>
                                            <?php if ($status === 'awaiting_payment'): ?>
                                                <a class="booking-item__action" href="<?= DEFAULT_URL ?>public/Payment/show?booking_id=<?= (int) $b['id'] ?>">Complete payment</a>
                                            <?php endif; ?>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </section>
                    </main>
                <?php endif; ?>
            <?php
                break;

            case 'wishlist':
            ?>
                <?php if (empty($wishlistList)): ?>
                    <main class="main empty-state">
                        <div class="empty-state__content">
                            <h3 class="empty-state__title">No items in your wishlist</h3>
                            <p class="empty-state__subtitle">Looks empty… for now.</p>
                            <a href="<?= DEFAULT_URL ?>public/Home/index" class="empty-state__btn">Explore stays</a>
                        </div>
                    </main>
                <?php else: ?>
                    <main class="main">
                        <section class="wishlist">
                            <div class="wishlist__head">
                                <h2 class="wishlist__title">Wishlist</h2>
                                <p class="wishlist__subtitle"><?= count($wishlistList) ?> saved <?= count($wishlistList) === 1 ? 'property' : 'properties' ?></p>
                            </div>

                            <div class="wishlist-grid">
                                <?php foreach ($wishlistList as $w): ?>
                                    <article class="wish-card" data-property-id="<?= (int) $w['id'] ?>">
                                        <a class="wish-card__media" href="<?= DEFAULT_URL ?>public/Property/showOne?id=<?= (int) $w['id'] ?>">
                                            <?php if (!empty($w['image'])): ?>
                                                <img src="<?= DEFAULT_URL ?>assets/<?= htmlspecialchars($w['image']) ?>" alt="<?= htmlspecialchars($w['title']) ?>">
                                            <?php else: ?>
                                                <span class="wish-card__media--ph" aria-hidden="true"></span>
                                            <?php endif; ?>
                                        </a>

                                        <button type="button" class="wish-card__remove" data-id="<?= (int) $w['id'] ?>" aria-label="Remove from wishlist" title="Remove from wishlist">
                                            <img src="<?= DEFAULT_URL ?>assets/img/trash.svg" alt="">
                                        </button>

                                        <div class="wish-card__body">
                                            <a class="wish-card__name" href="<?= DEFAULT_URL ?>public/Property/showOne?id=<?= (int) $w['id'] ?>"><?= htmlspecialchars($w['title']) ?></a>
                                            <?php if (!empty($w['city'])): ?>
                                                <p class="wish-card__location"><?= htmlspecialchars($w['city']) ?></p>
                                            <?php endif; ?>
                                            <p class="wish-card__price"><?= number_format((float) $w['price_per_night'], 2) ?> &euro; <span>/ night</span></p>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    </main>

                    <script>
                        // Per-card wishlist removal. main.js only binds the first
                        // .fav-icon, so the grid handles its own clicks via delegation.
                        document.querySelector('.wishlist-grid')?.addEventListener('click', async (e) => {
                            const btn = e.target.closest('.wish-card__remove');
                            if (!btn) return;

                            const card = btn.closest('.wish-card');
                            const propertyId = btn.dataset.id;
                            btn.disabled = true;

                            try {
                                const res = await fetch('<?= DEFAULT_URL ?>public/Wishlist/toggle', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    body: JSON.stringify({ propertyId })
                                });
                                const data = await res.json();

                                if (data.status === 'success' && data.remove === true) {
                                    card.remove();
                                    showToast('success', 'Removed from wishlist');

                                    if (!document.querySelector('.wish-card')) {
                                        location.reload(); // fall back to the empty state
                                    }
                                } else {
                                    btn.disabled = false;
                                    showToast('error', 'Could not update your wishlist');
                                }
                            } catch (err) {
                                btn.disabled = false;
                                showToast('error', 'Could not update your wishlist');
                            }
                        });
                    </script>
                <?php endif; ?>
            <?php
                break;

            case 'messages':
            ?>
                <main class="main empty-state">
                    <div class="empty-state__content">
                        <h3 class="empty-state__title">No messages yet</h3>
                        <p class="empty-state__subtitle">Book a stay or contact a host to start chatting.</p>
                        <a href="<?= DEFAULT_URL ?>public/Home/index" class="empty-state__btn">Explore stays</a>
                    </div>
                </main>

            <?php
                break;

            case 'payments':
            ?>
                <main class="main empty-state">
                    <div class="empty-state__content">
                        <h3 class="empty-state__title">No payments yet</h3>
                        <p class="empty-state__subtitle">Your payment history will appear here.</p>
                        <a href="<?= DEFAULT_URL ?>public/home/index" class="empty-state__btn">Explore stays</a>
                    </div>
                </main>
            <?php
                break;

            case 'settings':
            ?>
                <main class="main settings-main">

                    <!-- ── PROFILE INFO ──────────────────────────────── -->
                    <section class="settings-section">
                        <h2 class="settings-section__title">Profile Settings</h2>
                        <p class="settings-section__subtitle">Manage your personal information and account preferences.</p>
                        <hr class="settings-divider">

                        <!-- Avatar -->
                        <div class="avatar-row">
                            <div class="avatar-wrapper">
                                <?php if (!empty($user['avatar_url'])): ?>
                                    <img src="<?= DEFAULT_URL ?>assets/img/users/<?= htmlspecialchars($user['avatar_url']) ?>" alt="Profile picture" class="avatar-img">
                                <?php else: ?>
                                    <div class="avatar-placeholder">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z" />
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="avatar-meta">
                                <span class="avatar-label">Profile Picture</span>
                                <div class="avatar-actions">
                                    <label for="avatar-upload" class="btn btn--primary btn--sm">Upload Image</label>
                                    <input type="file" id="avatar-upload" name="avatar" accept="image/*" class="visually-hidden">
                                    <button type="button" class="btn btn--ghost btn--sm" id="remove-avatar">
                                        <img src="<?= DEFAULT_URL ?>assets/img/trash.svg" alt="Trash">
                                        Remove
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Personal info form -->
                        <form action="/profile/update" method="POST" enctype="multipart/form-data" class="settings-form" id="profile-form">
                            <?php /* CSRF token if your framework uses one */ ?>
                            <!-- <input type="hidden" name="_token" value="<?= /* csrf_token() */ '' ?>"> -->
                            <?php

                            $parts = explode(' ', $user['name']);

                            $firstName = array_shift($parts);
                            $lastName = implode(' ', $parts);

                            ?>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label" for="first_name">First Name</label>
                                    <input class="form-input" type="text" id="first_name" name="first_name"
                                        placeholder="Placeholder"
                                        value="<?= htmlspecialchars($firstName ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="last_name">Last Name</label>
                                    <input class="form-input" type="text" id="last_name" name="last_name"
                                        placeholder="Placeholder"
                                        value="<?= htmlspecialchars($lastName ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="phone">Phone Number</label>
                                    <input class="form-input" type="tel" id="phone" name="phone"
                                        placeholder="Placeholder"
                                        value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="country">Country</label>
                                    <input class="form-input" type="text" id="country" name="country"
                                        placeholder="Placeholder"
                                        value="<?= htmlspecialchars($user['country'] ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="city">City</label>
                                    <input class="form-input" type="text" id="city" name="city"
                                        placeholder="Placeholder"
                                        value="<?= htmlspecialchars($user['city'] ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="email">Email</label>
                                    <div class="input-icon-wrapper">
                                        <input class="form-input form-input--icon" type="email" id="email" name="email"
                                            placeholder="example@example.com"
                                            value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                                        <span class="input-icon">
                                            <img src="<?= DEFAULT_URL ?>assets/img/lock.svg" alt="Lock icon">
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn--primary btn--save">Save Changes</button>
                        </form>
                    </section>

                    <hr class="settings-divider">

                    <!-- ── PASSWORD ──────────────────────────────────── -->
                    <section class="settings-section">
                        <h3 class="settings-section__title settings-section__title--md">Password</h3>
                        <p class="settings-section__subtitle">Modify your current password.</p>

                        <form action="<?= DEFAULT_URL ?>/profile/password" method="POST" class="settings-form" id="password-form">
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label" for="current_password">Current Password</label>
                                    <div class="input-icon-wrapper">
                                        <input class="form-input form-input--icon" type="password" id="current_password"
                                            name="current_password" value="••••••••••">
                                        <button type="button" class="input-icon input-icon--btn toggle-pw" data-target="current_password" aria-label="Toggle visibility">
                                            <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="new_password">New Password</label>
                                    <div class="input-icon-wrapper">
                                        <input class="form-input form-input--icon" type="password" id="new_password"
                                            name="new_password" value="••••••••••">
                                        <button type="button" class="input-icon input-icon--btn toggle-pw" data-target="new_password" aria-label="Toggle visibility">
                                            <img src="<?= DEFAULT_URL ?>assets/img/trash.svg" alt="Trash">
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </section>

                    <hr class="settings-divider">

                    <!-- ── PREFERENCES ───────────────────────────────── -->
                    <section class="settings-section">
                        <h3 class="settings-section__title settings-section__title--md">Preferences</h3>

                        <form action="/profile/preferences" method="POST" class="settings-form" id="preferences-form">

                            <!-- Language -->
                            <div class="form-group form-group--inline">
                                <label class="form-label" for="language">Language</label>
                                <div class="select-wrapper">
                                    <select class="form-select" id="language" name="language">
                                        <option value="es" <?= ($user['language'] ?? 'es') === 'es' ? 'selected' : '' ?>>Spanish</option>
                                        <option value="en" <?= ($user['language'] ?? '') === 'en' ? 'selected' : '' ?>>English</option>
                                        <option value="fr" <?= ($user['language'] ?? '') === 'fr' ? 'selected' : '' ?>>French</option>
                                        <option value="de" <?= ($user['language'] ?? '') === 'de' ? 'selected' : '' ?>>German</option>
                                        <option value="pt" <?= ($user['language'] ?? '') === 'pt' ? 'selected' : '' ?>>Portuguese</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Currency -->
                            <div class="form-group form-group--inline">
                                <label class="form-label" for="currency">Currency</label>
                                <div class="select-wrapper">
                                    <select class="form-select" id="currency" name="currency">
                                        <option value="EUR" <?= ($user['currency'] ?? 'EUR') === 'EUR' ? 'selected' : '' ?>>€ EUR</option>
                                        <option value="USD" <?= ($user['currency'] ?? '') === 'USD' ? 'selected' : '' ?>>$ USD</option>
                                        <option value="GBP" <?= ($user['currency'] ?? '') === 'GBP' ? 'selected' : '' ?>>£ GBP</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Notifications -->
                            <div class="form-group">
                                <span class="form-label">Notifications</span>
                                <div class="toggle-list">
                                    <label class="toggle-row">
                                        <span class="toggle-row__label">Email updates</span>
                                        <span class="toggle">
                                            <input type="checkbox" name="notif_email" value="1"
                                                <?= !empty($user['notif_email']) ? 'checked' : '' ?>>
                                            <span class="toggle__track"></span>
                                        </span>
                                    </label>
                                    <label class="toggle-row">
                                        <span class="toggle-row__label">Booking confirmations</span>
                                        <span class="toggle">
                                            <input type="checkbox" name="notif_bookings" value="1"
                                                <?= !empty($user['notif_bookings']) ? 'checked' : '' ?>>
                                            <span class="toggle__track"></span>
                                        </span>
                                    </label>
                                    <label class="toggle-row">
                                        <span class="toggle-row__label">Promotional emails</span>
                                        <span class="toggle">
                                            <input type="checkbox" name="notif_promo" value="1"
                                                <?= !empty($user['notif_promo']) ? 'checked' : '' ?>>
                                            <span class="toggle__track"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>

                        </form>
                    </section>

                    <hr class="settings-divider">

                    <!-- ── DELETE ACCOUNT ────────────────────────────── -->
                    <section class="settings-section">
                        <h3 class="settings-section__title settings-section__title--md">Delete Account</h3>

                        <form action="<?= DEFAULT_URL ?>public/User/delete" method="POST" id="delete-form">
                            <button type="submit" class="btn btn--danger">Delete my account</button>
                        </form>
                        <p class="settings-warning">This action is permanent and cannot be undone.</p>
                    </section>

                    <div id="deleteModal" class="modal hidden">
                        <div class="modal-box">
                            <button class="modal-close" id="closeModal">&times;</button>

                            <h3>Delete account</h3>
                            <p>This action is permanent and cannot be undone.</p>

                            <div class="modal-actions">
                                <button id="cancelBtn" class="btn btn-secondary">Cancel</button>
                                <button id="confirmDelete" class="btn btn-danger">Delete</button>
                            </div>
                        </div>
                    </div>

                    <?php if ($deleteError): ?>
                        <?php

                        $_SESSION['toast']['type'] = 'error';
                        $_SESSION['toast']['message'] = 'User has not been deleted';

                        ?>
                    <?php endif; ?>
                </main>
        <?php endswitch; ?>
    </div>

    <div id="toast-container"></div>

    <script src="<?= DEFAULT_URL ?>assets/js/toast.js"></script>
</body>

</html>