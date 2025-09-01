<?php
require_once __DIR__ . '/config/database.php';

// tiny esc helper
function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function imgp($p){ if(!$p) return ''; if(strpos($p, '../')===0) return substr($p, 3); return $p; }

// fetch a few latest cars to showcase (optional)
$cars = [];
try {
        $stmt = $pdo->query("SELECT id, name, brand, model, car_type, featured_image, main_location, daily_price FROM tblcars WHERE status IS NULL OR TRIM(status) = '' OR LOWER(TRIM(status)) = 'active' ORDER BY created_at DESC LIMIT 6");
  $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
  $cars = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Dreams Rent | Template</title>

    <link rel="shortcut icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="assets/plugins/aos/aos.css">
    <link rel="stylesheet" href="assets/css/feather.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/plugins/boxicons/css/boxicons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="main-wrapper">

    <!-- Header (kept as in HTML, but link to PHP) -->
    <header class="header">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg header-nav">
                <div class="navbar-header">
                    <a id="mobile_btn" href="javascript:void(0);">
                        <span class="bar-icon"><span></span><span></span><span></span></span>
                    </a>
                    <a href="index-2.php" class="navbar-brand logo">
                        <img src="assets/img/logo.svg" class="img-fluid" alt="Logo">
                    </a>
                    <a href="index-2.php" class="navbar-brand logo-small">
                        <img src="assets/img/logo-small.png" class="img-fluid" alt="Logo">
                    </a>
                </div>
                <div class="main-menu-wrapper">
                    <div class="menu-header">
                        <a href="index-2.php" class="menu-logo">
                            <img src="assets/img/logo.svg" class="img-fluid" alt="Logo">
                        </a>
                        <a id="menu_close" class="menu-close" href="javascript:void(0);"> <i class="fas fa-times"></i></a>
                    </div>
                    <ul class="main-nav">
                        <li class="has-submenu megamenu active">
                            <a href="index-2.php">Home <i class="fas fa-chevron-down"></i></a>
                            <ul class="submenu mega-submenu"><li><div class="megamenu-wrapper"><div class="row"></div></div></li></ul>
                        </li>
                        <li class="has-submenu">
                            <a href="#">Listings <i class="fas fa-chevron-down"></i></a>
                            <ul class="submenu">
                                <li><a href="listing-grid.php">Listing Grid</a></li>
                                <li><a href="listing-list.html">Listing List</a></li>
                                <li><a href="listing-map.html">Listing With Map</a></li>
                                <li><a href="listing-grid.php">Listing Details</a></li>
                            </ul>
                        </li>
                        <li class="has-submenu"><a href="#">Pages <i class="fas fa-chevron-down"></i></a><ul class="submenu"></ul></li>
                        <li class="has-submenu"><a href="#">Blog <i class="fas fa-chevron-down"></i></a><ul class="submenu"></ul></li>
                        <li class="has-submenu"><a href="#">Dashboard <i class="fas fa-chevron-down"></i></a></li>
                        <li class="login-link"><a href="register.html">Sign Up</a></li>
                        <li class="login-link"><a href="login.html">Sign In</a></li>
                    </ul>
                </div>
                <ul class="nav header-navbar-rht">
                    <li class="nav-item"><a class="nav-link header-login" href="login.html"><span><i class="fa-regular fa-user"></i></span>Sign In</a></li>
                    <li class="nav-item"><a class="nav-link header-reg" href="register.html"><span><i class="fa-solid fa-lock"></i></span>Sign Up</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Banner -->
    <section class="banner-section banner-slider">
        <div class="container">
            <div class="home-banner">
                <div class="row align-items-center">
                    <div class="col-lg-6" data-aos="fade-down">
                        <p class="explore-text"><span><i class="fa-solid fa-thumbs-up me-2"></i></span>100% Trusted car rental platform in the World</p>
                        <h1><span>Find Your Best</span><br>Dream Car for Rental</h1>
                        <p>Experience the ultimate in comfort, performance, and sophistication with our luxury car rentals.</p>
                        <div class="view-all">
                            <a href="listing-grid.php" class="btn btn-view d-inline-flex align-items-center">View all Cars <span><i class="feather-arrow-right ms-2"></i></span></a>
                        </div>
                    </div>
                    <div class="col-lg-6" data-aos="fade-down">
                        <div class="banner-imgs"><img src="assets/img/car-right.png" class="img-fluid aos" alt="bannerimage"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Optional: Latest cars preview replacing a portion of Popular Cars section -->
    <section class="section popular-services popular-explore">
        <div class="container">
            <div class="section-heading" data-aos="fade-down">
                <h2>Latest Cars</h2>
                <p>Freshly added by our admins</p>
            </div>
            <div class="row">
                <?php foreach ($cars as $c): ?>
                <div class="col-lg-4 col-md-6 col-12" data-aos="fade-down">
                    <div class="listing-item">
                        <div class="listing-img">
                            <a href="listing-details.php?id=<?= (int)$c['id'] ?>">
                                <img src="<?= h(imgp($c['featured_image']) ?: 'assets/img/cars/car-01.jpg') ?>" class="img-fluid" alt="car">
                            </a>
                            <span class="featured-text"><?= h($c['brand'] ?: ($c['car_type'] ?: '')) ?></span>
                        </div>
                        <div class="listing-content">
                            <div class="listing-features d-flex align-items-end justify-content-between">
                                <div class="list-rating">
                                    <h3 class="listing-title"><a href="listing-details.php?id=<?= (int)$c['id'] ?>"><?= h($c['name']) ?></a></h3>
                                </div>
                            </div>
                            <div class="listing-location-details">
                                <div class="listing-price"><span><i class="feather-map-pin"></i></span><?= h($c['main_location'] ?: '-') ?></div>
                                <div class="listing-price"><h6>$<?= number_format((float)$c['daily_price'], 2) ?> <span>/ Day</span></h6></div>
                            </div>
                            <div class="listing-button">
                                <a href="listing-details.php?id=<?= (int)$c['id'] ?>" class="btn btn-order"><span><i class="feather-calendar me-2"></i></span>Rent Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php if (!$cars): ?>
                <div class="col-12"><div class="alert alert-info">No cars yet. Please add some in Admin.</div></div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer (kept simple) -->
    <footer class="footer">
        <div class="footer-bottom"><div class="container"><div class="copyright"><div class="row align-items-center"><div class="col-md-12 text-center">&copy; <?= date('Y') ?> Dreams Rent</div></div></div></div></div>
    </footer>

</div>

<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/plugins/aos/aos.js"></script>
<script src="assets/plugins/select2/js/select2.min.js"></script>
<script src="assets/js/backToTop.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="assets/js/script.js"></script>
<script>AOS.init();</script>
</body>
</html>
