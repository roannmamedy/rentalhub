<?php
require_once __DIR__ . '/config/database.php';
function h($v){return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');}
function imgp($p){ if(!$p) return ''; if(strpos($p, '../')===0) return substr($p, 3); return $p; }
function decode_arr($v){
  if ($v === null || $v === '') return [];
  if (is_array($v)) return $v;
  $d = json_decode($v, true);
  if (is_array($d)) return $d;
  // double-decoding fallback
  $d2 = json_decode((string)$v, true);
  return is_array($d2) ? $d2 : [];
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { http_response_code(404); echo 'Car not found'; exit; }

// Fetch car
$stmt = $pdo->prepare("SELECT * FROM tblcars WHERE id = :id");
$stmt->execute([':id' => $id]);
$car = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$car) { http_response_code(404); echo 'Car not found'; exit; }

// Gallery images
$gstmt = $pdo->prepare("SELECT image_path FROM car_images WHERE car_id = :id ORDER BY is_featured DESC, id ASC");
$gstmt->execute([':id' => $id]);
$gallery = $gstmt->fetchAll(PDO::FETCH_COLUMN) ?: [];

$features = decode_arr($car['features_amenities'] ?? '');
$extras = decode_arr($car['extra_services'] ?? '');

// Related cars: all cars except current one
$rel = [];
// include fields needed for cards similar to listing-grid
$relSql = "SELECT c.id, c.name, c.featured_image, c.main_location, c.daily_price, c.brand, c.car_type,
                   c.transmission, c.odometer, c.fuel_type, c.year_of_car, c.passengers, c.seats
            FROM tblcars c
            WHERE c.id <> :id AND (c.status IS NULL OR TRIM(c.status)='' OR LOWER(TRIM(c.status))='active')
            ORDER BY c.created_at DESC LIMIT 8";
$rstmt = $pdo->prepare($relSql);
$rstmt->execute([':id'=>$id]);
$rel = $rstmt->fetchAll(PDO::FETCH_ASSOC);

// preload related car images for mini sliders
$relImagesById = [];
if ($rel) {
  $relIds = array_map(function($r){ return (int)$r['id']; }, $rel);
  $in = implode(',', $relIds);
  if ($in !== '') {
    foreach ($pdo->query("SELECT car_id, image_path FROM car_images WHERE car_id IN ($in) ORDER BY id ASC") as $row) {
      $relImagesById[(int)$row['car_id']][] = $row['image_path'];
    }
  }
}

// Helpers
$title = $car['name'] ?: 'Listing Details';
$cover = imgp($car['featured_image']) ?: 'assets/img/cars/car-01.jpg';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <title><?= h($title) ?></title>
  <link rel="shortcut icon" href="assets/img/favicon.png">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
  <link rel="stylesheet" href="assets/plugins/aos/aos.css">
  <link rel="stylesheet" href="assets/css/feather.css">
  <link rel="stylesheet" href="assets/plugins/fancybox/fancybox.css">
  <link rel="stylesheet" href="assets/plugins/boxicons/css/boxicons.min.css">
  <link rel="stylesheet" href="assets/plugins/slick/slick.css">
  <link rel="stylesheet" href="assets/plugins/slick/slick-theme.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    .slider-nav-thumbnails .slick-slide {
        padding: 0 5px;
    }
    .slider-nav-thumbnails .slick-slide img {
        width: 100%;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
    }
    .detail-bigimg .product-img img {
        width: 100%;
        height: 500px;
        object-fit: cover;
        border-radius: 12px;
    }
  /* Match listing-grid card image sizing */
  .card-thumb{width:100%;height:260px;object-fit:cover;background:#f5f5f7;border-radius:8px}
  .img-slider .img-fluid{width:100%;height:260px;object-fit:cover;border-radius:8px}
  </style>
</head>
<body>
  <div class="main-wrapper">
    <header class="header">
      <div class="container-fluid">
        <nav class="navbar navbar-expand-lg header-nav">
          <div class="navbar-header">
            <a id="mobile_btn" href="javascript:void(0);"><span class="bar-icon"><span></span><span></span><span></span></span></a>
            <a href="index-2.php" class="navbar-brand logo"><img src="assets/img/logo.svg" class="img-fluid" alt="Logo"></a>
            <a href="index-2.php" class="navbar-brand logo-small"><img src="assets/img/logo-small.png" class="img-fluid" alt="Logo"></a>
          </div>
          <div class="main-menu-wrapper">
            <div class="menu-header">
              <a href="index-2.php" class="menu-logo"><img src="assets/img/logo.svg" class="img-fluid" alt="Logo"></a>
              <a id="menu_close" class="menu-close" href="javascript:void(0);"> <i class="fas fa-times"></i></a>
            </div>
            <ul class="main-nav">
              <li class="has-submenu megamenu">
								<a href="index-2.php">Home <i class="fas fa-chevron-down"></i></a>
							</li>
							<li class="has-submenu active">
								<a href="#">Listings <i class="fas fa-chevron-down"></i></a>
								<ul class="submenu">
								    <li><a href="listing-grid.php">Listing Grid</a></li>
								    <li><a href="listing-list.html">Listing List</a></li>
								    <li class="active"><a href="listing-details.php">Listing Details</a></li>
								</ul>
							</li>
							<li class="has-submenu">
								<a href="#">Pages <i class="fas fa-chevron-down"></i></a>
								<ul class="submenu">
								    <li ><a href="about-us.html">About Us</a></li>
								    <li><a href="contact-us.html">Contact</a></li>
									<li class="has-submenu">
										<a href="javascript:void(0);">Authentication</a>
										<ul class="submenu">
											<li><a href="register.html">Sign Up</a></li>
											<li><a href="login.html">Sign In</a></li>
											<li><a href="forgot-password.html">Forgot Password</a></li>
											<li><a href="reset-password.html">Reset Password</a></li>
										</ul>
									</li>
									<li class="has-submenu">
										<a href="javascript:void(0);">Booking</a>
										<ul class="submenu">
											<li><a href="booking-checkout.html">Booking Checkout</a></li>
											<li><a href="booking.html">Booking</a></li>
											<li><a href="invoice-details.html">Invoice Details</a></li>
										</ul>
									</li>
									<li class="has-submenu">
										<a href="javascript:void(0);">Error Page</a>
										<ul class="submenu">
											<li><a href="error-404.html">404 Error</a></li>
											<li><a href="error-500.html">500 Error</a></li>
										</ul>
									</li>
								    <li><a href="pricing.html">Pricing</a></li>
								    <li><a href="faq.html">FAQ</a></li>
								    <li><a href="gallery.html">Gallery</a></li>
								    <li><a href="our-team.html">Our Team</a></li>
								    <li><a href="testimonial.html">Testimonials</a></li>
									<li><a href="terms-condition.html">Terms & Conditions</a></li>
									<li><a href="privacy-policy.html">Privacy Policy</a></li>
									<li><a href="maintenance.html">Maintenance</a></li>
									<li><a href="coming-soon.html">Coming Soon</a></li>
								</ul>
							</li>
							<li class="has-submenu">
								<a href="#">Blog <i class="fas fa-chevron-down"></i></a>
								<ul class="submenu">
								    <li><a href="blog-list.html">Blog List</a></li>
									<li><a href="blog-grid.html">Blog Grid</a></li>
									<li><a href="blog-details.html">Blog Details</a></li>
								</ul>
							</li>
              <li class="login-link">
								<a href="register.html">Sign Up</a>
							</li>
							<li class="login-link">
								<a href="login.html">Sign In</a>
							</li>
            </ul>
          </div>
          <ul class="nav header-navbar-rht">
            <li class="nav-item"><a class="nav-link header-login" href="login.html"><span><i class="fa-regular fa-user"></i></span>Sign In</a></li>
            <li class="nav-item"><a class="nav-link header-reg" href="register.html"><span><i class="fa-solid fa-lock"></i></span>Sign Up</a></li>
          </ul>
        </nav>
      </div>
    </header>

    <div class="breadcrumb-bar">
      <div class="container"><div class="row align-items-center text-center"><div class="col-md-12 col-12">
        <h2 class="breadcrumb-title"><?= h($title) ?></h2>
        <nav aria-label="breadcrumb" class="page-breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="index-2.php">Home</a></li><li class="breadcrumb-item"><a href="listing-grid.php">Listings</a></li><li class="breadcrumb-item active" aria-current="page"><?= h($title) ?></li></ol></nav>
      </div></div></div>
    </div>

    <section class="product-detail-head">
        <div class="container">
            <div class="detail-page-head">
                <div class="detail-headings">
                    <div class="star-rated">
                        <ul class="list-rating">
                            <li>
                                <div class="car-brand">
                                    <span><img src="assets/img/icons/car-icon.svg" alt="img"></span>
                                    <?= h($car['car_type'] ?: 'Car') ?>
                                </div>
                            </li>
                            <?php if (!empty($car['year_of_car'])): ?>
                            <li><span class="year"><?= h($car['year_of_car']) ?></span></li>
                            <?php endif; ?>
                            <li class="ratings">
                                <i class="fas fa-star filled"></i>
                                <i class="fas fa-star filled"></i>
                                <i class="fas fa-star filled"></i>
                                <i class="fas fa-star filled"></i>
                                <i class="fas fa-star filled"></i>
                                <span class="d-inline-block average-list-rating">(5.0)</span>
                            </li>
                        </ul>
                        <div class="camaro-info">
                            <h3><?= h($title) ?></h3>
                            <div class="camaro-location">
                                <div class="camaro-location-inner"><i class='bx bx-map'></i><span>Location : <?= h($car['main_location'] ?: '-') ?></span></div>
                                <div class="camaro-location-inner"><i class='bx bx-show'></i><span>Views : 250 </span></div>
                                <div class="camaro-location-inner"><i class='bx bx-car'></i><span>Listed on: <?= h(date('d M, Y', strtotime($car['created_at'] ?? 'now'))) ?></span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="details-btn">
                    <span class="total-badge"><i class='bx bx-calendar-edit'></i>Total Booking : 300</span>
                    <a href="#"> <i class='bx bx-git-compare'></i>Compare</a>
                </div>
            </div>
        </div>
    </section>

    <section class="section product-details">
      <div class="container">
        <div class="row">
          <div class="col-lg-8">
            <div class="detail-product">
              <div class="pro-info">
                <div class="pro-badge">
                  <span class="badge-km"><i class="fa-solid fa-person-walking"></i>4.2 Km Away</span>
                  <a href="javascript:void(0);" class="fav-icon"><i class="fa-regular fa-heart"></i></a>
                </div>
                <ul>
                    <li class="del-airport"><i class="fa-solid fa-check"></i>Airport delivery</li>
                    <li class="del-home"><i class="fa-solid fa-check"></i>Home delivery</li>
                </ul>
              </div>
              <div class="slider detail-bigimg">
                <div class="product-img"><img src="<?= h($cover) ?>" alt="Cover"></div>
                <?php foreach ($gallery as $img): $img = imgp($img); if ($img === $cover) continue; ?>
                  <div class="product-img"><img src="<?= h($img) ?>" alt="Gallery"></div>
                <?php endforeach; ?>
              </div>
              <div class="slider slider-nav-thumbnails">
                <div><img src="<?= h($cover) ?>" alt="thumb"></div>
                <?php foreach ($gallery as $img): $img = imgp($img); if ($img === $cover) continue; ?>
                  <div><img src="<?= h($img) ?>" alt="thumb"></div>
                <?php endforeach; ?>
              </div>
            </div>

            <?php if (!empty($extras)): ?>
            <div class="review-sec pb-0">
              <div class="review-header"><h4>Extra Service</h4></div>
              <div class="lisiting-service"><div class="row">
        <?php foreach ($extras as $ex): ?>
                  <div class="servicelist d-flex align-items-center col-xxl-3 col-xl-4 col-sm-6">
                    <div class="service-img"><img src="assets/img/icons/service-01.svg" alt="Icon"></div>
          <div class="service-info"><p><?= h(ucwords(str_replace('_',' ', (string)$ex))) ?></p></div>
                  </div>
                <?php endforeach; ?>
              </div></div>
            </div>
            <?php endif; ?>

            <?php if (!empty($car['description'])): ?>
            <div class="review-sec mb-0">
                <div class="review-header">
                    <h4>Description of Listing</h4>
                </div>
                <div class="description-list">
                    <p><?= nl2br(h($car['description'])) ?></p>
                </div>
            </div>
            <?php endif; ?>

            <div class="review-sec specification-card">
                <div class="review-header">
                    <h4>Specifications</h4>
                </div>
                <div class="card-body">
                    <div class="lisiting-featues">
                        <div class="row">
                            <div class="featureslist d-flex align-items-center col-xl-3 col-md-4 col-sm-6">
                                <div class="feature-img"><img src="assets/img/specification/specification-icon-1.svg" alt="Icon"></div>
                                <div class="featues-info"><span>Body</span><h6><?= h($car['car_type'] ?: '-') ?></h6></div>
                            </div>
                            <div class="featureslist d-flex align-items-center col-xl-3 col-md-4 col-sm-6">
                                <div class="feature-img"><img src="assets/img/specification/specification-icon-2.svg" alt="Icon"></div>
                                <div class="featues-info"><span>Make</span><h6><?= h($car['brand'] ?: '-') ?></h6></div>
                            </div>
                            <div class="featureslist d-flex align-items-center col-xl-3 col-md-4 col-sm-6">
                                <div class="feature-img"><img src="assets/img/specification/specification-icon-3.svg" alt="Icon"></div>
                                <div class="featues-info"><span>Transmission</span><h6><?= h($car['transmission'] ?: '-') ?></h6></div>
                            </div>
                            <div class="featureslist d-flex align-items-center col-xl-3 col-md-4 col-sm-6">
                                <div class="feature-img"><img src="assets/img/specification/specification-icon-4.svg" alt="Icon"></div>
                                <div class="featues-info"><span>Fuel Type</span><h6><?= h($car['fuel_type'] ?: '-') ?></h6></div>
                            </div>
                            <div class="featureslist d-flex align-items-center col-xl-3 col-md-4 col-sm-6">
                                <div class="feature-img"><img src="assets/img/specification/specification-icon-7.svg" alt="Icon"></div>
                                <div class="featues-info"><span>Year</span><h6><?= h($car['year_of_car'] ?: '-') ?></h6></div>
                            </div>
                            <div class="featureslist d-flex align-items-center col-xl-3 col-md-4 col-sm-6">
                                <div class="feature-img"><img src="assets/img/specification/specification-icon-10.svg" alt="Icon"></div>
                                <div class="featues-info"><span>Door</span><h6><?= h($car['doors'] ?: '-') ?> Doors</h6></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="review-sec listing-feature">
              <div class="review-header"><h4>Car Features</h4></div>
              <?php if (!empty($features)): ?>
              <div class="about-features"><div class="row">
                <?php foreach (array_chunk($features, max(1, ceil(count($features)/3))) as $chunk): ?>
                  <div class="col-md-4"><ul>
                    <?php foreach ($chunk as $f): ?>
                      <li><span><i class="bx bx-check-double"></i></span><?= h(ucwords(str_replace('_',' ', (string)$f))) ?></li>
                    <?php endforeach; ?>
                  </ul></div>
                <?php endforeach; ?>
              </div></div>
              <?php else: ?>
                <p class="text-muted">No features listed.</p>
              <?php endif; ?>
            </div>

            <div class="review-sec listing-feature">
              <div class="review-header"><h4>Tariff</h4></div>
              <div class="table-responsive">
                <table class="table border mb-3">
                  <thead class="thead-dark"><tr><th>Name</th><th>Daily Price</th><th>Base Kilometers</th><th>Kilometers Extra Price</th></tr></thead>
                  <tbody>
                    <tr>
                      <td>Daily</td>
                      <td>$<?= number_format((float)$car['daily_price'], 2) ?></td>
                      <td><?= h($car['base_kilometers_per_day'] ?? 0) ?></td>
                      <td>$<?= number_format((float)($car['kilometers_extra_price'] ?? 0), 2) ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="review-sec mb-0 pb-0">
              <div class="review-header"><h4>Gallery</h4></div>
              <div class="gallery-list">
                <div class="row">
                  <?php if ($cover): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-6 mb-3">
                      <div class="gallery-widget">
                        <a href="<?= h($cover) ?>" data-fancybox="gallery1">
                          <img class="img-fluid" alt="Image" src="<?= h($cover) ?>" style="height: 120px; object-fit: cover; width: 100%; border-radius: 8px;">
                        </a>
                      </div>
                    </div>
                  <?php endif; ?>
                  <?php foreach ($gallery as $img): $img = imgp($img); ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-6 mb-3">
                      <div class="gallery-widget">
                        <a href="<?= h($img) ?>" data-fancybox="gallery1">
                          <img class="img-fluid" alt="Image" src="<?= h($img) ?>" style="height: 120px; object-fit: cover; width: 100%; border-radius: 8px;">
                        </a>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            </div>

            <div class="review-sec mb-0">
                <div class="review-header">
                    <h4>Video</h4>
                </div>
                <div class="short-video">
                    <img class="img-fluid" alt="Image" src="assets/img/video-img.jpg">
                    <a href="https://www.youtube.com/embed/ExJZAegsOis" data-fancybox="video" class="video-icon">
                        <i class="bx bx-play"></i>
                    </a>
                </div>
            </div>

            <div class="review-sec faq-feature">
                <div class="review-header">
                    <h4>FAQ’s</h4>
                </div>
                <div class="faq-info">
                    <div class="faq-card">
                        <h6 class="card-title">
                            <a class="collapsed" data-bs-toggle="collapse" href="#faqone" aria-expanded="false">What is the cancellation policy?</a>
                        </h6>
                        <div id="faqone" class="card-collapse collapse" style="">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>
                    </div>
                    <div class="faq-card">
                        <h6 class="card-title">
                            <a class="collapsed" data-bs-toggle="collapse" href="#faqtwo" aria-expanded="false">What are the payment options?</a>
                        </h6>
                        <div id="faqtwo" class="card-collapse collapse">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>
                    </div>
                </div>
            </div>
          </div>

          <div class="col-lg-4 theiaStickySidebar">
            <div class="review-sec mt-0">
                <div class="review-header">
                    <h4>Pricing</h4>
                </div>
                <form class="mb-3" id="bookingTypeForm" method="get" action="booking-checkout.php">
                  <input type="hidden" name="car_id" value="<?= (int)$car['id'] ?>">
                  <?php
                    $rates = [
                      'daily'   => (float)($car['daily_price'] ?? 0),
                      'weekly'  => (float)($car['weekly_price'] ?? 0),
                      'monthly' => (float)($car['monthly_price'] ?? 0),
                      'yearly'  => (float)($car['yearly_price'] ?? 0),
                    ];
                    // Determine default selection
                    $defaultType = 'daily';
                    if ($rates['daily'] <= 0) {
                      foreach (['weekly','monthly','yearly'] as $t) {
                        if ($rates[$t] > 0) { $defaultType = $t; break; }
                      }
                    }
                    $labels = [ 'daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly', 'yearly' => 'Yearly' ];
                    foreach ($rates as $type => $price):
                      if ($price <= 0) continue; // show only available rates
                  ?>
                  <label class="booking_custom_check bookin-check-2">
                    <input type="radio" name="booking_type" value="<?= $type ?>" <?= $type === $defaultType ? 'checked' : '' ?>>
                    <span class="booking_checkmark">
                      <span class="checked-title"><?= $labels[$type] ?></span>
                      <span class="price-rate">$<?= number_format($price, 2) ?></span>
                    </span>
                  </label>
                  <?php endforeach; ?>
                </form>
                <div class="location-content">
                    <div class="delivery-tab">
                        <ul class="nav">
                            <li>
                                <label class="booking_custom_check">
                                    <input type="radio" name="rent_type" checked>
                                    <span class="booking_checkmark">
                                        <span class="checked-title">Delivery</span>
                                    </span>
                                </label>
                            </li>
                            <li>
                                <label class="booking_custom_check">
                                    <input type="radio" name="rent_type">
                                    <span class="booking_checkmark">
                                        <span class="checked-title">Self Pickup</span>
                                    </span>
                                </label>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="delivery">
              <form class="" method="get" action="booking-checkout.php" onsubmit="return true;">
                <input type="hidden" name="car_id" value="<?= (int)$car['id'] ?>">
                <input type="hidden" name="booking_type" id="selected_booking_type" value="daily">
                <ul>
                  <li class="column-group-last">
                    <div class="input-block mb-0">
                      <div class="search-btn">
                        <button type="submit" class="btn btn-primary check-available w-100">Book</button>
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#enquiry" class="btn btn-theme">Enquire Us</a>
                      </div>
                    </div>
                  </li>
                </ul>
              </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="review-sec extra-service mt-0">
              <div class="review-header"><h4>Listing Owner Details</h4></div>
              <div class="owner-detail">
                <div class="owner-img"><a href="#"><img src="assets/img/profiles/avatar-07.jpg" alt="User"></a><span class="badge-check"><img src="assets/img/icons/badge-check.svg" alt="User"></span></div>
                <div class="reviewbox-list-rating"><h5><a>Owner</a></h5></div>
              </div>
              <ul class="booking-list">
                <li>Email <span>info@example.com</span></li>
                <li>Phone Number <span>+1 14XXX XXX78</span></li>
                <li>Location <span><?= h($car['main_location'] ?: '-') ?></span></li>
              </ul>
            </div>

            <div class="review-sec share-car mt-0">
              <div class="review-header"><h4>View Car Location</h4></div>
              <iframe src="https://www.google.com/maps?q=<?= urlencode($car['main_location'] ?: 'USA') ?>&output=embed" class="iframe-video"></iframe>
            </div>
            <div class="review-sec share-car mt-0 mb-0">
                <div class="review-header">
                    <h4>Share</h4>
                </div>
                <ul class="nav-social">
                    <li><a href="javascript:void(0);"><i class="fa-brands fa-facebook-f"></i></a></li>
                    <li><a href="javascript:void(0);"><i class="fa-brands fa-twitter"></i></a></li>
                    <li><a href="javascript:void(0);"><i class="fa-brands fa-instagram"></i></a></li>
                    <li><a href="javascript:void(0);"><i class="fa-brands fa-linkedin-in"></i></a></li>
                    <li><a href="javascript:void(0);"><i class="fa-brands fa-youtube"></i></a></li>
                    <li><a href="javascript:void(0);"><i class="fa-brands fa-pinterest-p"></i></a></li>
                </ul>
            </div>
          </div>
        </div>

        <div class="row"><div class="col-md-12">
          <div class="details-car-grid">
            <div class="details-slider-heading"><h3>You May be Interested in</h3></div>
            <div class="owl-carousel rental-deal-slider details-car owl-theme">
              <?php foreach ($rel as $rc): ?>
                <?php
                  $slides = [];
                  if (!empty($rc['featured_image'])) $slides[] = imgp($rc['featured_image']);
                  if (!empty($relImagesById[$rc['id']])) {
                    foreach ($relImagesById[$rc['id']] as $ip) {
                      if (!in_array($ip, $slides)) $slides[] = imgp($ip);
                    }
                  }
                  if (empty($slides)) $slides[] = 'assets/img/cars/car-01.jpg';
                ?>
                <div class="rental-car-item">
                  <div class="listing-item">
                    <div class="listing-img">
                      <div class="img-slider owl-carousel">
                        <?php foreach ($slides as $s): ?>
                        <div class="slide-images">
                          <a href="listing-details.php?id=<?= (int)$rc['id'] ?>">
                            <img src="<?= h($s) ?>" class="img-fluid" alt="<?= h($rc['name']) ?>">
                          </a>
                        </div>
                        <?php endforeach; ?>
                      </div>
                      <div class="fav-item justify-content-end">
                        <a href="javascript:void(0)" class="fav-icon"><i class="feather-heart"></i></a>
                      </div>
                      <span class="featured-text"><?= h($rc['brand'] ?: ($rc['car_type'] ?: '')) ?></span>
                    </div>
                    <div class="listing-content">
                      <div class="listing-features d-flex align-items-end justify-content-between">
                        <div class="list-rating">
                          <h3 class="listing-title">
                            <a href="listing-details.php?id=<?= (int)$rc['id'] ?>"><?= h($rc['name']) ?></a>
                          </h3>
                          <div class="list-rating">
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star filled"></i>
                            <i class="fas fa-star"></i>
                            <span>(4.0) 138 Reviews</span>
                          </div>
                        </div>
                        <div class="list-km">
                          <span class="km-count"><img src="assets/img/icons/map-pin.svg" alt="loc">3.2m</span>
                        </div>
                      </div>
                      <div class="listing-details-group">
                        <ul>
                          <li>
                            <span><img src="assets/img/icons/car-parts-05.svg" alt="Transmission"></span>
                            <p><?= h($rc['transmission'] ?: 'Auto') ?></p>
                          </li>
                          <li>
                            <span><img src="assets/img/icons/car-parts-02.svg" alt="Odometer"></span>
                            <p><?= h(($rc['odometer'] !== null && $rc['odometer'] !== '') ? $rc['odometer'].' KM' : '—') ?></p>
                          </li>
                          <li>
                            <span><img src="assets/img/icons/car-parts-03.svg" alt="Fuel"></span>
                            <p><?= h($rc['fuel_type'] ?: 'Petrol') ?></p>
                          </li>
                        </ul>
                        <ul>
                          <li>
                            <span><img src="assets/img/icons/car-parts-04.svg" alt="Power"></span>
                            <p>Power</p>
                          </li>
                          <li>
                            <span><img src="assets/img/icons/car-parts-05.svg" alt="Year"></span>
                            <p><?= h($rc['year_of_car'] ?: '—') ?></p>
                          </li>
                          <li>
                            <span><img src="assets/img/icons/car-parts-06.svg" alt="Persons"></span>
                            <p><?= h(($rc['passengers'] ?: $rc['seats'] ?: 4)) ?> Persons</p>
                          </li>
                        </ul>
                      </div>
                      <div class="listing-location-details">
                        <div class="listing-price"><span><i class="feather-map-pin"></i></span><?= h($rc['main_location'] ?: '-') ?></div>
                        <div class="listing-price"><h6>$<?= number_format((float)$rc['daily_price'], 2) ?> <span>/ Day</span></h6></div>
                      </div>
                      <div class="listing-button">
                        <a href="listing-details.php?id=<?= (int)$rc['id'] ?>" class="btn btn-order">
                          <span><i class="feather-calendar me-2"></i></span>Rent Now
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div></div>

      </div>
    </section>

   	 <!-- Footer -->
		 <footer class="footer">	
			<!-- Footer Top -->	
			<div class="footer-top aos" data-aos="fade-down">
				<div class="container">
					<div class="row">
						<div class="col-lg-7">
							<div class="row">
								<div class="col-lg-4 col-md-6">
									<!-- Footer Widget -->
									<div class="footer-widget footer-menu">
										<h5 class="footer-title">About Company</h5>
										<ul>
											<li>
												<a href="about.html">Our Company</a>
											</li>
											<li>
												<a href="javascript:void(0)">Shop Toyota</a>
											</li>
											<li>
												<a href="javascript:void(0)">Dreamsrentals USA</a>
											</li>
											<li>
												<a href="javascript:void(0)">Dreamsrentals Worldwide</a>
											</li>
											<li>
												<a href="javascript:void(0)">Dreamsrental Category</a>
											</li>										
										</ul>
									</div>
									<!-- /Footer Widget -->
								</div>
								<div class="col-lg-4 col-md-6">
									<!-- Footer Widget -->
									<div class="footer-widget footer-menu">
										<h5 class="footer-title">Vehicles Type</h5>
										<ul>
											<li>
												<a href="javascript:void(0)">All  Vehicles</a>
											</li>
											<li>
												<a href="javascript:void(0)">SUVs</a>
											</li>
											<li>
												<a href="javascript:void(0)">Trucks</a>
											</li>
											<li>
												<a href="javascript:void(0)">Cars</a>
											</li>
											<li>
												<a href="javascript:void(0)">Crossovers</a>
											</li>								
										</ul>
									</div>
									<!-- /Footer Widget -->
								</div>
								<div class="col-lg-4 col-md-6">
									<!-- Footer Widget -->
									<div class="footer-widget footer-menu">
										<h5 class="footer-title">Quick links</h5>
										<ul>
											<li>
												<a href="javascript:void(0)">My Account</a>
											</li>
											<li>
												<a href="javascript:void(0)">Champaigns</a>
											</li>
											<li>
												<a href="javascript:void(0)">Dreamsrental Dealers</a>
											</li>
											<li>
												<a href="javascript:void(0)">Deals and Incentive</a>
											</li>
											<li>
												<a href="javascript:void(0)">Financial Services</a>
											</li>								
										</ul>
									</div>
									<!-- /Footer Widget -->
								</div>
							</div>
						</div>
						<div class="col-lg-5">
							<div class="footer-contact footer-widget">
								<h5 class="footer-title">Contact Info</h5>
								<div class="footer-contact-info">									
									<div class="footer-address">											
										<span><i class="feather-phone-call"></i></span>
										<div class="addr-info">
											<a href="tel:+1(888)7601940">+ 1 (888) 760 1940</a>
										</div>
									</div>
									<div class="footer-address">
										<span><i class="feather-mail"></i></span>
										<div class="addr-info">
											<a href="mailto:support@example.com">support@example.com</a>
										</div>
									</div>
									<div class="update-form">
										<form action="#">
											<span><i class="feather-mail"></i></span> 
											<input type="email" class="form-control" placeholder="Enter You Email Here">
											<button type="submit" class="btn btn-subscribe"><span><i class="feather-send"></i></span></button>
										</form>
									</div>
								</div>								
								<div class="footer-social-widget">
									<ul class="nav-social">
										<li>
											<a href="javascript:void(0)"><i class="fa-brands fa-facebook-f fa-facebook fi-icon"></i></a>
										</li>
										<li>
											<a href="javascript:void(0)"><i class="fab fa-instagram fi-icon"></i></a>
										</li>
										<li>
											<a href="javascript:void(0)"><i class="fab fa-behance fi-icon"></i></a>
										</li>
										<li>
											<a href="javascript:void(0)"><i class="fab fa-twitter fi-icon"></i> </a>
										</li>
										<li>
											<a href="javascript:void(0)"><i class="fab fa-linkedin fi-icon"></i></a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>					
				</div>
			</div>
			<!-- /Footer Top -->

			<!-- Footer Bottom -->
			<div class="footer-bottom">
				<div class="container">
					<!-- Copyright -->
					<div class="copyright">
						<div class="row align-items-center">
							<div class="col-md-6">
								<div class="copyright-text">
									<p>© 2024 Dreams Rent. All Rights Reserved.</p>
								</div>
							</div>
							<div class="col-md-6">
								<!-- Copyright Menu -->
								<div class="copyright-menu">
									<div class="vistors-details">
										<ul class="d-flex">											
											<li><a href="javascript:void(0)"><img class="img-fluid" src="assets/img/icons/paypal.svg" alt="Paypal"></a></li>											
											<li><a href="javascript:void(0)"><img class="img-fluid" src="assets/img/icons/visa.svg" alt="Visa"></a></li>
											<li><a href="javascript:void(0)"><img class="img-fluid" src="assets/img/icons/master.svg" alt="Master"></a></li>
											<li><a href="javascript:void(0)"><img class="img-fluid" src="assets/img/icons/applegpay.svg" alt="applegpay"></a></li>
										</ul>										   								
									</div>
								</div>
								<!-- /Copyright Menu -->
							</div>
						</div>
					</div>
					<!-- /Copyright -->
				</div>
			</div>
			<!-- /Footer Bottom -->			
		</footer>
		<!-- /Footer -->

  </div>

  <div class="modal new-modal fade enquire-mdl" id="enquiry" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Enquiry</h4>
                <button type="button" class="close-btn" data-bs-dismiss="modal"><span>×</span></button>
            </div>
            <div class="modal-body">
                <form action="#">
                    <div class="modal-form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" placeholder="Enter Name">
                    </div>
                    <div class="modal-form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" placeholder="Enter Email Address">
                    </div>
                    <div class="modal-form-group">
                        <label>Phone Number</label>
                        <input type="text" class="form-control" placeholder="Enter Phone Number">
                    </div>
                    <div class="modal-form-group">
                        <label>Message</label>
                        <textarea class="form-control" rows="4"></textarea>
                    </div>
                    <label class="custom_check w-100">
                        <input type="checkbox" name="username">
                        <span class="checkmark"></span> I Agree with <a href="javascript:void(0);">Terms of Service</a> & <a href="javascript:void(0);">Privacy Policy</a>
                    </label>
                    <div class="modal-btn modal-btn-sm">
                        <button type="submit" class="btn btn-primary w-100">Send Enquiry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
  </div>

  <script src="assets/js/jquery-3.7.1.min.js"></script>
  <script src="assets/js/bootstrap.bundle.min.js"></script>
  <script src="assets/plugins/aos/aos.js"></script>
  <script src="assets/plugins/moment/moment.min.js"></script>
  <script src="assets/js/bootstrap-datetimepicker.min.js"></script>
  <script src="assets/plugins/slick/slick.js"></script>
  <script src="assets/js/owl.carousel.min.js"></script>
  <script src="assets/plugins/select2/js/select2.min.js"></script>
  <script src="assets/js/backToTop.js"></script>
  <script src="assets/plugins/theia-sticky-sidebar/ResizeSensor.js"></script>
  <script src="assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js"></script>
  <script src="assets/plugins/fancybox/fancybox.umd.js"></script>
  <script src="assets/js/script.js"></script>
  <script>
    AOS.init();
    $(function(){
      if ($('.detail-bigimg').length && $('.slider-nav-thumbnails').length) {
        $('.detail-bigimg').slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          arrows: true,
          fade: false,
          asNavFor: '.slider-nav-thumbnails'
        });
        $('.slider-nav-thumbnails').slick({
          slidesToShow: 4,
          slidesToScroll: 1,
          asNavFor: '.detail-bigimg',
          dots: false,
          focusOnSelect: true
        });
      }
      $('.rental-deal-slider').owlCarousel({
        items: 3,
        loop: true,
        margin: 24,
        nav: true,
        dots: false,
        responsive:{0:{items:1},768:{items:2},1200:{items:3}}
      });
      // init inner sliders for each related card
      $('.details-car .img-slider').each(function(){
        $(this).owlCarousel({
          items:1,
          loop: true,
          margin:0,
          nav:true,
          dots:false
        });
      });
      // Sync selected booking type into the hidden field on the Book form
      const formRadios = document.querySelectorAll('#bookingTypeForm input[name="booking_type"]');
      const hiddenType = document.getElementById('selected_booking_type');
      function syncType(){
        const sel = document.querySelector('#bookingTypeForm input[name="booking_type"]:checked');
        if (sel && hiddenType) hiddenType.value = sel.value;
      }
      formRadios.forEach(r => r.addEventListener('change', syncType));
      syncType();
    });
  </script>
</body>
</html>
