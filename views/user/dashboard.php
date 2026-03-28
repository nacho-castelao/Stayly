<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>User Dashboard</title>
    <link rel="stylesheet" href="<?= DEFAULT_URL ?>assets/css/style.css" />
    <link rel="stylesheet" href="<?= DEFAULT_URL ?>assets/css/dashboard.css" />
</head>

<body>

    <div class="dashboard">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="logo"></div>

            <nav class="menu">
                <div>
                    <a class="menu-item active">
                        <img src="<?= DEFAULT_URL ?>assets/img/dashboard/layout-dashboard.svg" class="icon" />
                        Dashboard
                    </a>

                    <a class="menu-item">
                        <img src="<?= DEFAULT_URL ?>assets/img/dashboard/calendar-check-dashboard.svg" class="icon" />
                        My Bookings
                    </a>

                    <a class="menu-item">
                        <img src="<?= DEFAULT_URL ?>assets/img/dashboard/heart-dashboard.svg" class="icon" />
                        Wishlist
                    </a>

                    <a class="menu-item">
                        <img src="<?= DEFAULT_URL ?>assets/img/dashboard/message-square-dashboard.svg" class="icon" />
                        Messages
                    </a>

                    <a class="menu-item">
                        <img src="<?= DEFAULT_URL ?>assets/img/dashboard/credit-card-dashboard.svg" class="icon" />
                        Payments
                    </a>

                    <a class="menu-item">
                        <img src="<?= DEFAULT_URL ?>assets/img/dashboard/settings-dashboard.svg" class="icon" />
                        Profile Settings
                    </a>
                </div>


                <div class="menu-bottom">
                    <a class="menu-item">
                        <img src="<?= DEFAULT_URL ?>assets/img/dashboard/info-dashboard.svg" class="icon" />
                        Help
                    </a>

                    <a class="menu-item">
                        <img src="<?= DEFAULT_URL ?>assets/img/dashboard/log-out-dashboard.svg" class="icon" />
                        Log Out
                    </a>
                </div>
            </nav>
        </aside>

        <!-- MAIN -->
        <main class="main">

            <!-- TOP BAR -->
            <header class="topbar">
                <h3>Dashboard</h3>

                <div class="user">
                    <img src="" class="avatar" />
                    <img src="<?= DEFAULT_URL ?>assets/img/dashboard/chevron-down-dashboard.svg" class="chevron" />
                </div>
            </header>

            <!-- CONTENT -->
            <section class="content">
                <div class="welcome">
                    <h2>Hi Nacho</h2>
                    <p>Here’s your activity review</p>
                </div>

                <!-- CARDS -->
                <div class="cards">

                    <div class="card">
                        <img src="<?= DEFAULT_URL ?>assets/img/dashboard/calendar-days-dashboard.svg" class="card-icon" />
                        <h2>3</h2>
                        <p>Upcoming bookings</p>
                    </div>

                    <div class="card">
                        <img src="<?= DEFAULT_URL ?>assets/img/dashboard/heart2-dashboard.svg" class="card-icon" />
                        <h2>5</h2>
                        <p>Saved properties</p>
                    </div>

                    <div class="card">
                        <img src="<?= DEFAULT_URL ?>assets/img/dashboard/message-square2-dashboard.svg" class="card-icon" />
                        <h2>1</h2>
                        <p>New messages</p>
                    </div>

                    <div class="card">
                        <img src="<?= DEFAULT_URL ?>assets/img/dashboard/credit-card2-dashboard.svg" class="card-icon" />
                        <h2>1</h2>
                        <p>Payments pending</p>
                    </div>

                    <div class="card">
                        <img src="<?= DEFAULT_URL ?>assets/img/dashboard/flag-dashboard.svg" class="card-icon" />
                        <h2>6</h2>
                        <p>Trips completed</p>
                    </div>

                    <div class="card">
                        <img src="<?= DEFAULT_URL ?>assets/img/dashboard/gauge-dashboard.svg" class="card-icon" />
                        <h2>60%</h2>
                        <p>Profile completion</p>
                    </div>

                </div>
            </section>
        </main>
    </div>
</body>

</html>