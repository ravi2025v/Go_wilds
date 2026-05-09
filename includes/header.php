<?php
// includes/header.php
if (session_status() == PHP_SESSION_NONE) {
    session_name("GoWilds_Session");
    session_start();
}
require_once 'admin/includes/db.php';
$is_logged_in = isset($_SESSION['user_id']);

// Sync user phone if missing from session
if ($is_logged_in && !isset($_SESSION['user_phone'])) {
    require_once 'admin/includes/db.php';
    $header_user_id = $_SESSION['user_id'];
    $header_user_res = $conn->query("SELECT phone FROM users WHERE id = $header_user_id");
    if ($header_user_res && $header_user_res->num_rows > 0) {
        $header_user_data = $header_user_res->fetch_assoc();
        $_SESSION['user_phone'] = $header_user_data['phone'];
    }
}
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <!--====== Required meta tags ======-->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="description" content="Adventure, Tours, Travel">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--====== Title ======-->
    <title><?php echo isset($title) ? $title : 'MyEasyTrip - We Make Your Travel Easy'; ?></title>

    <?php
    $wishlist_count = 0;
    if ($is_logged_in) {
        $wc_res = $conn->query("SELECT COUNT(id) as total FROM wishlist WHERE user_id = " . $_SESSION['user_id']);
        if ($wc_res) {
            $wc_data = $wc_res->fetch_assoc();
            $wishlist_count = $wc_data['total'];
        }
    }
    ?>

    <!--====== Jquery js ======-->
    <script src="assets/vendor/jquery-3.6.0.min.js"></script>
    <!--====== Bootstrap js ======-->
    <script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>

    <script>
        window.is_logged_in = <?php echo ($is_logged_in ? 'true' : 'false'); ?>;
    </script>
    <!--====== Favicon Icon ======-->
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/png">
    <!--====== Google Fonts ======-->
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!--====== Flaticon css ======-->
    <link rel="stylesheet" href="assets/fonts/flaticon/flaticon_gowilds.css">
    <!--====== FontAwesome css ======-->
    <link rel="stylesheet" href="assets/fonts/fontawesome/css/all.min.css">
    <!--====== Bootstrap css ======-->
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <!--====== magnific-popup css ======-->
    <link rel="stylesheet" href="assets/vendor/magnific-popup/dist/magnific-popup.css">
    <!--====== Slick-popup css ======-->
    <link rel="stylesheet" href="assets/vendor/slick/slick.css">
    <!--====== Jquery UI css ======-->
    <link rel="stylesheet" href="assets/vendor/jquery-ui/jquery-ui.min.css">
    <!--====== Nice Select css ======-->
    <link rel="stylesheet" href="assets/vendor/nice-select/css/nice-select.css">
    <!--====== Animate css ======-->
    <link rel="stylesheet" href="assets/vendor/animate.css">
    <!--====== Default css ======-->
    <link rel="stylesheet" href="assets/css/default.css">
    <!--====== Style css ======-->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Swiper Slider (Required for Tour Gallery) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <style>
        /* Essential theme overrides only */
        .header-area {
            position: relative !important;
            z-index: 9999 !important;
            /* Force header to the very front */
        }

        .main-menu .navigation>li>.sub-menu {
            z-index: 99999 !important;
            /* Drodowns must be higher than header */
        }

        .nice-select .list {
            z-index: 99999 !important;
        }

        <style>
        /* Modern Mobile Header Fixes */
        @media (max-width: 1199px) {
            .site-brading {
                display: block !important;
                padding: 10px 0;
            }

            .site-brading .brand-logo img {
                height: 45px !important;
                width: auto !important;
                max-width: 180px !important;
                display: inline-block !important;
            }

            .header-navigation .primary-menu {
                display: flex !important;
                align-items: center;
                justify-content: space-between;
                padding: 10px 15px !important;
            }

            .nav-menu:not(.menu-on) {
                display: none !important;
            }

            .header-top-bar {
                display: none !important;
            }
        }

        @media (max-width: 575px) {
            .site-brading .brand-logo img {
                height: 38px !important;
            }
        }

        /* Custom Logo Preloader Styles */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #fff;
            z-index: 999999;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .preloader .loader {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 150px;
            height: 150px;
            background: transparent;
        }

        .logo-loader {
            width: 100px;
            animation: logoPulse 2s ease-in-out infinite;
            z-index: 2;
        }

        .logo-loader img {
            width: 100%;
            height: auto;
        }

        .loader-ring {
            position: absolute;
            width: 140px;
            height: 140px;
            border: 2px solid rgba(245, 166, 35, 0.1);
            border-top: 2px solid #f5a623;
            border-radius: 50%;
            animation: ringRotate 2s linear infinite;
        }

        /* Plane following the ring */
        .loader-ring::after {
            content: "\f0fb";
            /* fa-plane */
            font-family: "Font Awesome 5 Pro", "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            top: 2px;
            left: 50%;
            transform: translateX(-50%) rotate(90deg);
            color: #f5a623;
            font-size: 18px;
            z-index: 10;
        }

        @keyframes logoPulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.9;
            }

            50% {
                transform: scale(1.05);
                opacity: 1;
            }
        }

        @keyframes ringRotate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Top Header Height Fix */
        .header-top-bar {
            padding: 1px 0 !important;
            min-height: auto !important;
        }

        .header-top-bar .single-info-item-two,
        .header-top-bar .booking-item,
        .header-top-bar ul {
            margin-bottom: 0 !important;
        }

        .header-top-bar p {
            margin-bottom: 0 !important;
            line-height: 1.2 !important;
        }

        .header-top-bar .inner-info {
            padding: 5px 0 !important;
        }
    </style>
</head>

<body>
    <!--====== Start Preloader ======-->
    <div class="preloader">
        <div class="loader">
            <div class="logo-loader">
                <img src="assets/images/logo.png" alt="MyEasyTrip Logo">
            </div>
            <div class="loader-ring"></div>
        </div>
    </div>
    <script>
        // Professional Preloader Logic: Show for assets, but never get stuck
        (function () {
            var hidePreloader = function () {
                var p = document.querySelector('.preloader');
                if (p) {
                    p.style.transition = 'opacity 0.5s ease';
                    p.style.opacity = '0';
                    setTimeout(function () { p.style.display = 'none'; }, 500);
                }
            };
            // Safety timeout: Auto-hide after 3.5 seconds if window.load fails
            setTimeout(hidePreloader, 3500);
            window.addEventListener('load', hidePreloader);
        })();
    </script>
    <!--====== End Preloader ======-->

    <!--====== Search From ======-->
    <div class="modal fade search-modal" id="search-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form>
                    <div class="form_group">
                        <input type="search" class="form_control" placeholder="Search here" name="search">
                        <label><i class="fa fa-search"></i></label>
                    </div>
                </form>
            </div>
        </div>
    </div><!--====== Search From ======-->
    <!--====== Start Header ======-->
    <header class="header-area header-three ">
        <!--====== Header Top Bar ======-->
        <div class="header-top-bar bg-green ">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-6 col-lg-12">
                        <!--====== Information Wrapper ======-->
                        <div class="information-wrapper">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="single-info-item-two justify-content-start">
                                        <div class="inner-info text-Start">
                                            <div class="icon">
                                                <i class="fas fa-phone-alt"></i>
                                            </div>
                                            <div class="info2">
                                                <p><a href="tel:+918233803333">+91-8233803333</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="single-info-item-two justify-content-start">
                                        <div class="inner-info">
                                            <div class="icon">
                                                <i class="far fa-envelope"></i>
                                            </div>
                                            <div class="info">
                                                <p><a href="mailto:support@myeasytrip.in">support@myeasytrip.in</a>
                                                <p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-12">
                        <div class="row align-items-center">
                            <div class="col-lg-9 ">
                                <ul class="d-flex justify-content-end align-items-center">
                                    <?php if ($is_logged_in): ?>
                                        <li class="pe-3 text-white"><i class="fas fa-user-circle me-2"></i>Hello,
                                            <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Traveler'); ?>
                                        </li>
                                        <li class="pe-3"><a href="my-bookings.php"><i class="fas fa-briefcase me-1"></i>My
                                                Bookings</a></li>
                                        <li class="pe-3"><a href="logout.php"><i
                                                    class="fas fa-sign-out-alt me-1"></i>Logout</a></li>
                                    <?php else: ?>
                                        <li class="pe-3"><a href="login.php"><i class="fas fa-user-lock me-1"></i>Login /
                                                Register</a></li>
                                    <?php endif; ?>
                                    <!-- <li class="pe-3"><a href="about.php">About Us</a></li> -->
                                </ul>
                            </div>
                            <div class="col-lg-3">
                                <div class="booking-item">
                                    <div class="bk-item booking-user" id="currency">
                                        <select class="wide">
                                            <option value="01">USD</option>
                                            <option value="02" selected>INR</option>
                                            <option value="03">EUR</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--====== Header Navigation ======-->
        <div class="header-navigation navigation-white">
            <div class="nav-overlay"></div>
            <div class="container">
                <div class="primary-menu black-bg px-0">
                    <!--====== Site Branding ======-->
                    <div class="site-brading ">
                        <a href="index.php" class="brand-logo"><img src="assets/images/logo.png"
                                alt="MyEasyTrip Logo"></a>
                    </div>
                    <!--====== Nav Menu ======-->
                    <div class="nav-menu">
                        <!--=== Nav Search ===-->
                        <div class="nav-search mb-30 d-block d-xl-none ">
                            <form action="search-results.php" method="GET">
                                <div class="form_group">
                                    <input type="text" class="form_control" placeholder="Search Tours..." name="search"
                                        required>
                                    <button class="search-btn"><i class="fas fa-search"></i></button>
                                </div>
                            </form>
                        </div>
                        <!--====== Main Menu ======-->
                        <nav class="main-menu nav-right-item">
                            <ul>
                                <li class="menu-item has-children"><a href="index.php">Home</a></li>
                                <li class="menu-item has-children"><a href="about.php">About Us</a></li>
                                <li class="menu-item has-children"><a href="tour.php">Tours</a>
                                    <ul class="sub-menu">
                                        <?php
                                        // Master list of categories to match Admin Panel
                                        $master_categories = [
                                            "Domestic Destinations",
                                            "International Destinations",
                                            "Hot Destinations",
                                            "Niche Experience Tours",
                                            "Pilgrimage",
                                            "Wild Safari",
                                            "Trek",
                                            "General"
                                        ];

                                        foreach ($master_categories as $cat) {
                                            echo '<li><a href="tour.php?category=' . urlencode($cat) . '">' . htmlspecialchars($cat) . '</a></li>';
                                        }

                                        // Also show any other unique categories that might exist in the DB
                                        $nav_cat_query = "SELECT DISTINCT category FROM tours WHERE category NOT IN ('" . implode("','", $master_categories) . "') AND status = 'active' ORDER BY category";
                                        $nav_cat_res = $conn->query($nav_cat_query);
                                        if ($nav_cat_res && $nav_cat_res->num_rows > 0) {
                                            while ($nav_cat = $nav_cat_res->fetch_assoc()) {
                                                echo '<li><a href="tour.php?category=' . urlencode($nav_cat['category']) . '">' . htmlspecialchars($nav_cat['category']) . '</a></li>';
                                            }
                                        }
                                        ?>
                                    </ul>
                                </li>
                                <li class="menu-item has-children"><a href="https://www.myeasytrip.in/">Flight
                                        bookings</a></li>
                                <li class="menu-item has-children"><a href="visa-service.php">Visa services</a></li>
                                <li class="menu-item"><a href="activity.php">Activities</a></li>
                                <li class="menu-item"><a href="blog.php">Blog</a></li>
                                <li class="menu-item"><a href="wishlist.php">Wishlist <span
                                            class="badge bg-primary wishlist-badge" id="header-wishlist-count"
                                            style="font-size: 10px; vertical-align: top; border-radius: 50%; padding: 3px 6px; <?php echo $wishlist_count == 0 ? 'display:none;' : ''; ?>"><?php echo $wishlist_count; ?></span></a>
                                </li>
                                <li class="menu-item"><a href="contact.php">Contact</a></li>
                            </ul>

                        </nav>

                        <!--====== Menu Button ======-->
                        <div class="menu-button mt-40 d-xl-none">
                            <a href="contact.php" class="main-btn secondary-btn">Book Now<i
                                    class="fas fa-paper-plane"></i></a>
                        </div>
                    </div>
                    <!--====== Nav Right Item ======-->
                    <div class="nav-right-item">
                        <div class="navbar-toggler">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!--====== End Header ======-->