<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= DEFAULT_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= DEFAULT_URL ?>assets/css/toast.css">
    <link rel="stylesheet" href="<?= DEFAULT_URL ?>assets/css/calendar.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="" defer></script>
    <script src="<?= DEFAULT_URL ?>assets/js/calendar.js" defer></script>
    <script type="module" src="<?= DEFAULT_URL ?>assets/js/main.js" defer></script>

    <title>Stayly</title>
</head>

<body>
    <header>
        <div class="logo">
            <a href="<?= DEFAULT_URL ?>/public/index.php">
                <img src="<?= DEFAULT_URL ?>assets/img/Logo.svg" alt="Stayly logo">
            </a>
        </div>

        <div id="headerForm">
            <img src="<?= DEFAULT_URL ?>assets/img/Magnifyingglass.svg" alt="Magnifying glass">

            <input type="text" name="search" id="search" placeholder="Search destinations...">

            <img src="<?= DEFAULT_URL ?>assets/img/icons8-calendar-21.svg" alt="Icon calendar">
        </div>

        <div class="options">
            <img src="<?= DEFAULT_URL ?>assets/img/Shopping_Cart_01.svg" alt="Shopping cart">

            <img src="<?= DEFAULT_URL ?>assets/img/User.svg" class="user-icon" alt="User Icon">

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="drop-down drop-down-logged">
                    <a href="<?= DEFAULT_URL ?>public/User/showDashboard?page=dashboard">
                        <img src="<?= DEFAULT_URL ?>assets/img/ArrowDoor.svg" alt="Profile settings">

                        <span>Profile settings</span>
                    </a>
                    <a href="">
                        <img src="<?= DEFAULT_URL ?>assets/img/calendar.svg" alt="My Bookings">

                        <span>My Bookings</span>
                    </a>

                    <a href="">
                        <img src="<?= DEFAULT_URL ?>assets/img/heart (3).svg" alt="Wishlist">

                        <span>Wishlist</span>
                    </a>
                    <a href="">
                        <img src="<?= DEFAULT_URL ?>assets/img/message-square (1).svg" alt="Messages">

                        <span>Messages</span>
                    </a>
                    <a href="">
                        <img src="<?= DEFAULT_URL ?>assets/img/credit-card (2).svg" alt="Payments">

                        <span>Payments</span>
                    </a>

                    <a href="<?= DEFAULT_URL ?>public/User/logout">
                        <img src="<?= DEFAULT_URL ?>assets/img/log-out.svg" alt="Log out">

                        <span>Log out</span>
                    </a>
                </div>
            <?php else: ?>
                <div class="drop-down drop-down-default ">
                    <a href="<?= DEFAULT_URL ?>public/Home/showLogin">
                        <img src="<?= DEFAULT_URL ?>assets/img/arrowDoor.svg" alt="Log in">

                        <span>Log in</span>
                    </a>
                    <a href="<?= DEFAULT_URL ?>public/Home/showRegister">
                        <img src="<?= DEFAULT_URL ?>assets/img/user-plus 1.svg" alt="Register">

                        <span>Register</span>
                    </a>
                    <a href="<?= DEFAULT_URL ?>public/Home/showHost">
                        <img src="<?= DEFAULT_URL ?>assets/img/house 1.svg" alt="Become a host">

                        <span>Become a host</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </header>
    <main>