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

// Related cars: same brand or type (exclude current), fallback latest
$rel = [];
$rstmt = $pdo->prepare("SELECT c.id, c.name, c.featured_image, c.main_location, c.daily_price, c.brand, c.car_type
                        FROM tblcars c
                        WHERE c.id <> :id AND (c.brand = :brand OR c.car_type = :ctype)
                          AND (c.status IS NULL OR TRIM(c.status)='' OR LOWER(TRIM(c.status))='active')
                        ORDER BY c.created_at DESC LIMIT 8");
$rstmt->execute([':id'=>$id, ':brand'=>$car['brand'] ?? '', ':ctype'=>$car['car_type'] ?? '']);
$rel = $rstmt->fetchAll(PDO::FETCH_ASSOC);
if (!$rel) {
  $rstmt = $pdo->prepare("SELECT c.id, c.name, c.featured_image, c.main_location, c.daily_price, c.brand, c.car_type
                          FROM tblcars c WHERE c.id <> :id AND (c.status IS NULL OR TRIM(c.status)='' OR LOWER(TRIM(c.status))='active')
                          ORDER BY c.created_at DESC LIMIT 8");
  $rstmt->execute([':id'=>$id]);
  $rel = $rstmt->fetchAll(PDO::FETCH_ASSOC);
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
              <li class="has-submenu"><a href="index-2.php">Home</a></li>
              <li class="has-submenu active"><a href="#">Listings <i class="fas fa-chevron-down"></i></a>
                <ul class="submenu">
                  <li><a href="listing-grid.php">Listing Grid</a></li>
                  <li><a href="#">Listing List</a></li>
                  <li class="active"><a href="#">Listing Details</a></li>
                </ul>
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
                <li><div class="car-brand"><span><img src="assets/img/icons/car-icon.svg" alt="img"></span><?= h($car['car_type'] ?: 'Car') ?></div></li>
                <?php if (!empty($car['year_of_car'])): ?><li><span class="year"><?= h($car['year_of_car']) ?></span></li><?php endif; ?>
              </ul>
              <div class="camaro-info">
                <h3><?= h($title) ?></h3>
                <div class="camaro-location">
                  <div class="camaro-location-inner"><i class='bx bx-map'></i><span>Location : <?= h($car['main_location'] ?: '-') ?></span></div>
                  <div class="camaro-location-inner"><i class='bx bx-car'></i><span>Listed on: <?= h(date('d M, Y', strtotime($car['created_at'] ?? 'now'))) ?></span></div>
                </div>
              </div>
            </div>
          </div>
          <div class="details-btn">
            <span class="total-badge"><i class='bx bx-calendar-edit'></i>Daily Price : $<?= number_format((float)$car['daily_price'], 2) ?></span>
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
                  <a href="javascript:void(0);" class="fav-icon"><i class="fa-regular fa-heart"></i></a>
                </div>
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
                    <div class="service-info"><p><?= h($ex) ?></p></div>
                  </div>
                <?php endforeach; ?>
              </div></div>
            </div>
            <?php endif; ?>

            <?php if (!empty($car['description'])): ?>
            <div class="review-sec listing-feature">
              <div class="review-header"><h4>Description</h4></div>
              <p><?= nl2br(h($car['description'])) ?></p>
            </div>
            <?php endif; ?>

            <div class="review-sec listing-feature">
              <div class="review-header"><h4>Specifications</h4></div>
              <div class="table-responsive">
                <table class="table border mb-0">
                  <tbody>
                    <tr><th>Brand</th><td><?= h($car['brand'] ?: '-') ?></td><th>Model</th><td><?= h($car['model'] ?: '-') ?></td></tr>
                    <tr><th>Type</th><td><?= h($car['car_type'] ?: '-') ?></td><th>Fuel</th><td><?= h($car['fuel_type'] ?: '-') ?></td></tr>
                    <tr><th>Transmission</th><td><?= h($car['transmission'] ?: '-') ?></td><th>Color</th><td><?= h($car['color'] ?: '-') ?></td></tr>
                    <tr><th>Odometer</th><td><?= h($car['odometer'] ?: '-') ?></td><th>Passengers</th><td><?= h($car['passengers'] ?: '-') ?></td></tr>
                    <tr><th>Seats</th><td><?= h($car['seats'] ?: '-') ?></td><th>Doors</th><td><?= h($car['doors'] ?: '-') ?></td></tr>
                    <tr><th>Air Bags</th><td><?= h($car['air_bags'] ?: '-') ?></td><th>Year</th><td><?= h($car['year_of_car'] ?: '-') ?></td></tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="review-sec listing-feature">
              <div class="review-header"><h4>Car Features</h4></div>
              <?php if (!empty($features)): ?>
              <div class="about-features"><div class="row">
                <?php foreach (array_chunk($features, max(1, ceil(count($features)/3))) as $chunk): ?>
                  <div class="col-md-4"><ul>
                    <?php foreach ($chunk as $f): ?><li><span><i class="bx bx-check-double"></i></span><?= h($f) ?></li><?php endforeach; ?>
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

            <!-- Video (commented as requested)
            <div class="review-sec mb-0"><div class="review-header"><h4>Video</h4></div>
              <div class="short-video"><img class="img-fluid" alt="Image" src="assets/img/video-img.jpg">
                <a href="#" data-fancybox="video" class="video-icon"><i class="bx bx-play"></i></a>
              </div>
            </div> -->

            <!-- FAQ (commented as requested)
            <div class="review-sec faq-feature"><div class="review-header"><h4>FAQ’s</h4></div>
              <div class="faq-info">...</div>
            </div> -->

            <!-- Reviews and Leave a Reply (commented as requested) -->

          </div>

          <div class="col-lg-4 theiaStickySidebar">
            <div class="review-sec mt-0">
              <div class="review-header"><h4>Pricing</h4></div>
              <div class="mb-3">
                <label class="booking_custom_check bookin-check-2">
                  <input type="radio" name="price_rate" checked>
                  <span class="booking_checkmark"><span class="checked-title">Daily</span><span class="price-rate">$<?= number_format((float)$car['daily_price'], 2) ?></span></span>
                </label>
              </div>
              <div class="location-content">
                <div class="delivery-tab">
                  <ul class="nav">
                    <li><label class="booking_custom_check"><input type="radio" name="rent_type" checked><span class="booking_checkmark"><span class="checked-title">Delivery</span></span></label></li>
                    <li><label class="booking_custom_check"><input type="radio" name="rent_type"><span class="booking_checkmark"><span class="checked-title">Self Pickup</span></span></label></li>
                  </ul>
                </div>
                <div class="tab-content">
                  <div class="tab-pane fade active show" id="delivery">
                    <form class="">
                      <ul>
                        <li class="column-group-last">
                          <div class="input-block mb-0">
                            <div class="search-btn">
                              <a href="booking-checkout.html" class="btn btn-primary check-available w-100">Book</a>
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
          </div>
        </div>

        <div class="row"><div class="col-md-12">
          <div class="details-car-grid">
            <div class="details-slider-heading"><h3>You May be Interested in</h3></div>
            <div class="owl-carousel rental-deal-slider details-car owl-theme">
              <?php foreach ($rel as $rc): ?>
              <div class="rental-car-item">
                <div class="listing-item pb-0">
                  <div class="listing-img">
                    <a href="listing-details.php?id=<?= (int)$rc['id'] ?>"><img src="<?= h(imgp($rc['featured_image']) ?: 'assets/img/cars/car-03.jpg') ?>" class="img-fluid" alt="car"></a>
                    <div class="fav-item justify-content-end"><a href="javascript:void(0)" class="fav-icon"><i class="feather-heart"></i></a></div>
                    <span class="featured-text"><?= h($rc['brand'] ?: ($rc['car_type'] ?: '')) ?></span>
                  </div>
                  <div class="listing-content">
                    <div class="listing-features d-flex align-items-end justify-content-between">
                      <div class="list-rating">
                        <h3 class="listing-title"><a href="listing-details.php?id=<?= (int)$rc['id'] ?>"><?= h($rc['name']) ?></a></h3>
                      </div>
                    </div>
                    <div class="listing-details-group">
                      <ul>
                        <li><span><img src="assets/img/icons/car-parts-05.svg" alt="Manual"></span><p><?= h($rc['car_type'] ?: 'Type') ?></p></li>
                        <li><span><img src="assets/img/icons/map-pin.svg" alt="loc"></span><p><?= h($rc['main_location'] ?: '-') ?></p></li>
                      </ul>
                    </div>
                    <div class="listing-location-details">
                      <div class="listing-price"><span><i class="feather-map-pin"></i></span><?= h($rc['main_location'] ?: '-') ?></div>
                      <div class="listing-price"><h6>$<?= number_format((float)$rc['daily_price'], 2) ?> <span>/ Day</span></h6></div>
                    </div>
                    <div class="listing-button"><a href="listing-details.php?id=<?= (int)$rc['id'] ?>" class="btn btn-order"><span><i class="feather-calendar me-2"></i></span>Rent Now</a></div>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div></div>

      </div>
    </section>

    <footer class="footer"><div class="footer-bottom"><div class="container"><div class="copyright"><div class="row align-items-center"><div class="col-md-12 text-center">&copy; <?= date('Y') ?> Dreams Rent</div></div></div></div></div></footer>

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
    });
  </script>
</body>
</html>
