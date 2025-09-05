<?php
require_once __DIR__ . '/config/database.php';
function h($v){return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');}
function imgp($p){ if(!$p) return ''; if(strpos($p, '../')===0) return substr($p, 3); return $p; }

// paging / filters
$perPage = max(1, (int)($_GET['per'] ?? 5));
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;
$q = trim($_GET['q'] ?? '');

// sort
$sort = trim($_GET['sort'] ?? 'newest');

// incoming filters (arrays allowed)
$filterBrands = $_GET['brand'] ?? [];
$filterCategories = $_GET['category'] ?? [];
$filterYears = $_GET['year'] ?? [];
$filterFuel = $_GET['fuel'] ?? [];
$filterTrans = $_GET['transmission'] ?? [];
$filterPassengers = $_GET['passengers'] ?? [];
$minPrice = isset($_GET['min_price']) ? (float)$_GET['min_price'] : null;
$maxPrice = isset($_GET['max_price']) ? (float)$_GET['max_price'] : null;

// Build WHERE
$where = "(c.status IS NULL OR TRIM(c.status)='' OR LOWER(TRIM(c.status))='active')";
$params = [];
if ($q !== '') {
  $where .= " AND (c.name LIKE :q OR c.brand LIKE :q OR c.model LIKE :q OR c.main_location LIKE :q)";
  $params[':q'] = "%$q%";
}

if (!empty($filterBrands)) {
  $placeholders = [];
  foreach ((array)$filterBrands as $i => $b) {
    $k = ":brand_$i";
    $placeholders[] = $k;
    $params[$k] = strtolower(trim($b));
  }
  $where .= ' AND LOWER(TRIM(c.brand)) IN (' . implode(',', $placeholders) . ')';
}
if (!empty($filterCategories)) {
  $placeholders = [];
  foreach ((array)$filterCategories as $i => $v) {
    $k = ":cat_$i";
    $placeholders[] = $k;
    $params[$k] = strtolower(trim($v));
  }
  $where .= ' AND LOWER(TRIM(c.car_type)) IN (' . implode(',', $placeholders) . ')';
}
if (!empty($filterYears)) {
  $placeholders = [];
  foreach ((array)$filterYears as $i => $v) {
    $k = ":y_$i";
    $placeholders[] = $k;
    $params[$k] = (int)$v;
  }
  $where .= ' AND c.year_of_car IN (' . implode(',', $placeholders) . ')';
}
if (!empty($filterFuel)) {
  $placeholders = [];
  foreach ((array)$filterFuel as $i => $v) {
    $k = ":f_$i";
    $placeholders[] = $k;
    $params[$k] = strtolower(trim($v));
  }
  $where .= ' AND LOWER(TRIM(c.fuel_type)) IN (' . implode(',', $placeholders) . ')';
}
if (!empty($filterTrans)) {
  $placeholders = [];
  foreach ((array)$filterTrans as $i => $v) {
    $k = ":t_$i";
    $placeholders[] = $k;
    $params[$k] = strtolower(trim($v));
  }
  $where .= ' AND LOWER(TRIM(c.transmission)) IN (' . implode(',', $placeholders) . ')';
}
if (!empty($filterPassengers)) {
  $placeholders = [];
  foreach ((array)$filterPassengers as $i => $v) {
    $k = ":p_$i";
    $placeholders[] = $k;
    $params[$k] = (int)$v;
  }
  $where .= ' AND (c.passengers IN (' . implode(',', $placeholders) . ') OR c.seats IN (' . implode(',', $placeholders) . '))';
}
if ($minPrice !== null) { $where .= ' AND c.daily_price >= :min_price'; $params[':min_price'] = $minPrice; }
if ($maxPrice !== null) { $where .= ' AND c.daily_price <= :max_price'; $params[':max_price'] = $maxPrice; }

// Count total
$countSql = "SELECT COUNT(*) FROM tblcars c WHERE $where";
$cstmt = $pdo->prepare($countSql);
$cstmt->execute($params);
$total = (int)$cstmt->fetchColumn();

// Fetch page rows with image count
$orderSql = 'c.created_at DESC';
if ($sort === 'price_asc') $orderSql = 'c.daily_price ASC';
elseif ($sort === 'price_desc') $orderSql = 'c.daily_price DESC';
elseif ($sort === 'oldest') $orderSql = 'c.created_at ASC';

$sql = "SELECT c.id, c.name, c.brand, c.model, c.car_type, c.featured_image, c.main_location, c.daily_price, c.transmission, c.odometer, c.fuel_type, c.year_of_car, c.passengers, c.seats,
         COUNT(ci.id) AS img_count
  FROM tblcars c
  LEFT JOIN car_images ci ON ci.car_id = c.id
  WHERE $where
  GROUP BY c.id
  ORDER BY $orderSql
  LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
foreach ($params as $k=>$v) { $stmt->bindValue($k, $v); }
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pages = max(1, (int)ceil($total / $perPage));
function build_qs($overrides = []){
  $params = $_GET;
  foreach ($overrides as $k=>$v) {
    if ($v === null) {
      unset($params[$k]);
    } else {
      $params[$k] = $v;
    }
  }
  return '?'.http_build_query($params);
}

// Fetch distinct values for sidebar filters (small sets)
$optStmt = $pdo->query("SELECT DISTINCT TRIM(LOWER(brand)) AS brand FROM tblcars WHERE brand IS NOT NULL AND TRIM(brand)<>'' ORDER BY brand LIMIT 100");
$brandsList = $optStmt->fetchAll(PDO::FETCH_COLUMN);
$optStmt = $pdo->query("SELECT DISTINCT TRIM(LOWER(car_type)) AS cat FROM tblcars WHERE car_type IS NOT NULL AND TRIM(car_type)<>'' ORDER BY cat LIMIT 100");
$catsList = $optStmt->fetchAll(PDO::FETCH_COLUMN);
$optStmt = $pdo->query("SELECT DISTINCT year_of_car FROM tblcars WHERE year_of_car IS NOT NULL ORDER BY year_of_car DESC LIMIT 50");
$yearsList = $optStmt->fetchAll(PDO::FETCH_COLUMN);
$optStmt = $pdo->query("SELECT DISTINCT TRIM(LOWER(fuel_type)) FROM tblcars WHERE fuel_type IS NOT NULL AND TRIM(fuel_type)<>'' LIMIT 20");
$fuelsList = $optStmt->fetchAll(PDO::FETCH_COLUMN);
$optStmt = $pdo->query("SELECT DISTINCT TRIM(LOWER(transmission)) FROM tblcars WHERE transmission IS NOT NULL AND TRIM(transmission)<>'' LIMIT 10");
$transList = $optStmt->fetchAll(PDO::FETCH_COLUMN);

// Preload images for each car to render thumbnails as carousel
$imagesByCar = [];
if ($cars) {
  $ids = array_map(function($c){ return (int)$c['id']; }, $cars);
  $in = implode(',', $ids);
  $imgSql = "SELECT car_id, image_path FROM car_images WHERE car_id IN ($in) ORDER BY id ASC";
  foreach ($pdo->query($imgSql) as $r) {
    $imagesByCar[$r['car_id']][] = $r['image_path'];
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <title>Car Listings</title>
  <link rel="shortcut icon" href="assets/img/favicon.png">
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
  <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
  <link rel="stylesheet" href="assets/plugins/aos/aos.css">
  <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" href="assets/plugins/ion-rangeslider/css/ion.rangeSlider.min.css">
  <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
  <link rel="stylesheet" href="assets/css/feather.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="main-wrapper listing-page">
  <style>
    .img-slider .img-fluid{width:100%;height:260px;object-fit:cover;border-radius:8px}
  </style>

  <header class="header">
    <div class="container-fluid">
      <nav class="navbar navbar-expand-lg header-nav">
        <div class="navbar-header">
          <a id="mobile_btn" href="javascript:void(0);"><span class="bar-icon"><span></span><span></span><span></span></span></a>
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
            <a id="menu_close" class="menu-close" href="javascript:void(0);">
              <i class="fas fa-times"></i>
            </a>
          </div>
          <ul class="main-nav">
            <li class="has-submenu megamenu">
              <a href="index-2.php">
                Home <i class="fas fa-chevron-down"></i>
              </a>
              <ul class="submenu mega-submenu">
                <li>
                  <div class="megamenu-wrapper"></div>
                </li>
              </ul>
            </li>
            <li class="has-submenu active">
              <a href="#">Listings <i class="fas fa-chevron-down"></i></a>
              <ul class="submenu">
                <li>
                  <a href="listing-grid.php">Listing Grid</a>
                </li>
                <li class="active">
                  <a href="listing-list.php">Listing List</a>
                </li>
                <li>
                  <a href="listing-map.html">Listing With Map</a>
                </li>
                <li>
                  <a href="listing-details.html">Listing Details</a>
                </li>
              </ul>
            </li>
            <li class="has-submenu">
              <a href="#">Pages <i class="fas fa-chevron-down"></i></a>
              <ul class="submenu">
                <li>
                  <a href="about-us.html">About Us</a>
                </li>
                <li>
                  <a href="contact-us.html">Contact</a>
                </li>
                <li class="has-submenu">
                  <a href="javascript:void(0);">Authentication</a>
                  <ul class="submenu"></ul>
                </li>
                <li class="has-submenu">
                  <a href="javascript:void(0);">Booking</a>
                  <ul class="submenu"></ul>
                </li>
                <li class="has-submenu">
                  <a href="javascript:void(0);">Error Page</a>
                  <ul class="submenu"></ul>
                </li>
                <li>
                  <a href="pricing.html">Pricing</a>
                </li>
                <li>
                  <a href="faq.html">FAQ</a>
                </li>
                <li>
                  <a href="gallery.html">Gallery</a>
                </li>
                <li>
                  <a href="our-team.html">Our Team</a>
                </li>
                <li>
                  <a href="testimonial.html">Testimonials</a>
                </li>
                <li>
                  <a href="terms-condition.html">Terms & Conditions</a>
                </li>
                <li>
                  <a href="privacy-policy.html">Privacy Policy</a>
                </li>
                <li>
                  <a href="maintenance.html">Maintenance</a>
                </li>
                <li>
                  <a href="coming-soon.html">Coming Soon</a>
                </li>
              </ul>
            </li>
            <li class="has-submenu">
              <a href="#">Blog <i class="fas fa-chevron-down"></i></a>
              <ul class="submenu">
                <li>
                  <a href="blog-list.html">Blog List</a>
                </li>
                <li>
                  <a href="blog-grid.html">Blog Grid</a>
                </li>
                <li>
                  <a href="blog-details.html">Blog Details</a>
                </li>
              </ul>
            </li>
            <li class="has-submenu">
              <a href="#">Dashboard <i class="fas fa-chevron-down"></i></a>
              <ul class="submenu">
                <li class="has-submenu">
                  <a href="javascript:void(0);">User Dashboard</a>
                  <ul class="submenu"></ul>
                </li>
                <li class="has-submenu">
                  <a href="javascript:void(0);">Admin Dashboard</a>
                  <ul class="submenu"></ul>
                </li>
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
    <div class="container"><div class="row align-items-center text-center"><div class="col-md-12 col-12"><h2 class="breadcrumb-title">Car Listings</h2>
      <nav aria-label="breadcrumb" class="page-breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="index-2.php">Home</a>
          </li>
          <li class="breadcrumb-item">
            <a href="javascript:void(0);">Listings</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">Car Listings</li>
        </ol>
      </nav>
    </div></div></div>
  </div>

  <!-- Search -->
  <div class="section-search page-search">
    <div class="container">
      <div class="search-box-banner">
        <form action="listing-list.php">
          <ul class="align-items-center">
            <li class="column-group-main">
              <div class="input-block">
                <label>Pickup Location</label>
                <div class="group-img">
                  <input type="text" class="form-control" placeholder="Enter City, Airport, or Address">
                  <span>
                    <i class="feather-map-pin"></i>
                  </span>
                </div>
              </div>
            </li>
            <li class="column-group-main">
              <div class="input-block">
                <label>Pickup Date</label>
              </div>
              <div class="input-block-wrapp">
                <div class="input-block date-widget">
                  <div class="group-img">
                    <input type="text" class="form-control datetimepicker" placeholder="04/11/2023">
                    <span>
                      <i class="feather-calendar"></i>
                    </span>
                  </div>
                </div>
                <div class="input-block time-widge">
                  <div class="group-img">
                    <input type="text" class="form-control timepicker" placeholder="11:00 AM">
                    <span>
                      <i class="feather-clock"></i>
                    </span>
                  </div>
                </div>
              </div>
            </li>
            <li class="column-group-main">
              <div class="input-block">
                <label>Return Date</label>
              </div>
              <div class="input-block-wrapp">
                <div class="input-block date-widge">
                  <div class="group-img">
                    <input type="text" class="form-control datetimepicker" placeholder="04/11/2023">
                    <span>
                      <i class="feather-calendar"></i>
                    </span>
                  </div>
                </div>
                <div class="input-block time-widge">
                  <div class="group-img">
                    <input type="text" class="form-control timepicker" placeholder="11:00 AM">
                    <span>
                      <i class="feather-clock"></i>
                    </span>
                  </div>
                </div>
              </div>
            </li>
            <li class="column-group-last">
              <div class="input-block">
                <div class="search-btn">
                  <button class="btn search-button" type="submit"> <i class="fa fa-search" aria-hidden="true"></i>Search</button>
                </div>
              </div>
            </li>
          </ul>
        </form>
      </div>
    </div>
  </div>

  <!-- Sort By -->
  <div class="sort-section">
    <div class="container">
      <div class="sortby-sec">
        <div class="sorting-div">
          <div class="row d-flex align-items-center">
            <div class="col-xl-4 col-lg-3 col-sm-12 col-12">
              <div class="count-search">
                <p>Showing <?= ($page-1)*$perPage + 1 ?>-<?= min($page*$perPage, $total) ?> of <?= $total ?> Cars</p>
              </div>
            </div>
            <div class="col-xl-8 col-lg-9 col-sm-12 col-12">
              <div class="product-filter-group">
                <div class="sortbyset">
                  <ul>
                    <li>
                      <span class="sortbytitle">Show : </span>
                      <div class="sorting-select select-one">
                        <select id="showSelect" class="form-control select">
                          <?php foreach ([5,10,15,20,30] as $opt): ?>
                            <option value="<?= $opt ?>" <?= $perPage===$opt ? 'selected' : '' ?>><?= $opt ?></option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </li>
                    <li>
                      <span class="sortbytitle">Sort By </span>
                      <div class="sorting-select select-two">
                        <select id="sortSelect" class="form-control select">
                          <option value="newest" <?= $sort==='newest' ? 'selected' : '' ?>>Newest</option>
                          <option value="oldest" <?= $sort==='oldest' ? 'selected' : '' ?>>Oldest</option>
                          <option value="price_asc" <?= $sort==='price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                          <option value="price_desc" <?= $sort==='price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                        </select>
                      </div>
                    </li>
                  </ul>
                </div>
                <div class="grid-listview">
                  <ul></ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Car List View -->
  <section class="section car-listing pt-0">
    <div class="container">
      <div class="row">
        <div class="col-xl-3 col-lg-4 col-sm-12 col-12 theiaStickySidebar">
          <form action="listing-list.php" method="get" autocomplete="off" class="sidebar-form">
            <div class="sidebar-heading">
              <h3>What Are You Looking For</h3>
            </div>
            <div class="product-search">
              <div class="form-custom">
                <input type="text" name="q" class="form-control" value="<?= h($_GET['q'] ?? '') ?>" placeholder="Search by name or brand">
                <span><img src="assets/img/icons/search.svg" alt="img"></span>
              </div>
            </div>
            <div class="product-availability">
              <h6>Availability</h6>
              <div class="status-toggle">
                <input id="mobile_notifications" class="check" type="checkbox" checked>
                <label for="mobile_notifications" class="checktoggle">checkbox</label>
              </div>
            </div>
            <div class="accord-list">
              <div class="accordion" id="accordionMain1">
                <div class="card-header-new" id="headingOne">
                  <h6 class="filter-title">
                    <a href="javascript:void(0);" class="w-100" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      Car Brand	
                      <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                    </a> 
                  </h6>
                </div>
                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"  data-bs-parent="#accordionMain1">
                  <div class="card-body-chat">
                    <div class="row">
                      <div class="col-md-12">
                        <div id="checkBoxes1">
                          <div class="selectBox-cont">
                            <?php foreach ($brandsList as $b): $bn = h($b); ?>
                              <label class="custom_check w-100">
                                <input type="checkbox" name="brand[]" value="<?= $bn ?>" <?= in_array($b, (array)$filterBrands) ? 'checked' : '' ?>>
                                <span class="checkmark"></span> <?= ucfirst($bn) ?>
                              </label>
                            <?php endforeach; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="accordion" id="accordionMain2">
                <div class="card-header-new" id="headingTwo">
                  <h6 class="filter-title">
                    <a href="javascript:void(0);" class="w-100 collapsed"  data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                      Car Category
                      <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                    </a> 
                  </h6>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"  data-bs-parent="#accordionMain2">
                  <div class="card-body-chat">
                    <div id="checkBoxes2">
                      <div class="selectBox-cont">
                        <?php foreach ($catsList as $cat): $cv = h($cat); ?>
                          <label class="custom_check w-100">
                            <input type="checkbox" name="category[]" value="<?= $cv ?>" <?= in_array($cat, (array)$filterCategories) ? 'checked' : '' ?>>
                            <span class="checkmark"></span> <?= ucfirst($cv) ?>
                          </label>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="accordion" id="accordionMain3">
                <div class="card-header-new" id="headingYear">
                  <h6 class="filter-title">
                    <a href="javascript:void(0);" class="w-100 collapsed"  data-bs-toggle="collapse" data-bs-target="#collapseYear" aria-expanded="false" aria-controls="collapseYear">
                      Year
                      <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                    </a> 
                  </h6>
                </div>
                <div id="collapseYear" class="collapse" aria-labelledby="headingYear"  data-bs-parent="#accordionMain3">
                  <div class="card-body-chat">
                    <div id="checkBoxes02">
                      <div class="selectBox-cont">
                        <?php foreach ($yearsList as $y): ?>
                          <label class="custom_check w-100">
                            <input type="checkbox" name="year[]" value="<?= (int)$y ?>" <?= in_array((string)$y, (array)$filterYears) ? 'checked' : '' ?>>
                            <span class="checkmark"></span> <?= (int)$y ?>
                          </label>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="accordion" id="accordionMain04">
                <div class="card-header-new" id="headingfuel">
                  <h6 class="filter-title">
                    <a href="javascript:void(0);" class="w-100 collapsed"  data-bs-toggle="collapse" data-bs-target="#collapsefuel" aria-expanded="true" aria-controls="collapsefuel">
                      Fuel Type
                      <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                    </a> 
                  </h6>
                </div>
                <div id="collapsefuel" class="collapse" aria-labelledby="headingfuel"  data-bs-parent="#accordionMain04">
                  <div class="card-body-chat">
                    <div class="fuel-list">
                      <ul>
                        <?php foreach ($fuelsList as $f): $fv = h($f); ?>
                          <li>
                            <div class="input-selection">
                              <input type="checkbox" name="fuel[]" value="<?= $fv ?>" id="fuel_<?= $fv ?>" <?= in_array($f, (array)$filterFuel) ? 'checked' : '' ?>>
                              <label for="fuel_<?= $fv ?>"><?= ucfirst($fv) ?></label>
                            </div>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="accordion" id="accordionMain5">
                <div class="card-header-new" id="headingmileage">
                  <h6 class="filter-title">
                    <a href="javascript:void(0);" class="w-100 collapsed"  data-bs-toggle="collapse" data-bs-target="#collapsemileage" aria-expanded="true" aria-controls="collapsemileage">
                      Mileage
                      <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                    </a> 
                  </h6>
                </div>
                <div id="collapsemileage" class="collapse" aria-labelledby="headingmileage"  data-bs-parent="#accordionMain5">
                  <div class="card-body-chat">
                    <div class="fuel-list">
                      <ul>
                        <li>
                          <div class="input-selection">
                            <input type="radio" name="mileage" id="limited" value="red" checked>
                            <label for="limited">Limited</label>
                          </div>
                        </li>
                        <li>
                          <div class="input-selection">
                            <input type="radio" name="mileage" id="unlimited" value="red" checked>
                            <label for="unlimited">Unlimited</label>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="accordion" id="accordionMain6">
                <div class="card-header-new" id="headingrental">
                  <h6 class="filter-title">
                    <a href="javascript:void(0);" class="w-100 collapsed"  data-bs-toggle="collapse" data-bs-target="#collapserental" aria-expanded="true" aria-controls="collapserental">
                      Rental Type
                      <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                    </a> 
                  </h6>
                </div>
                <div id="collapserental" class="collapse" aria-labelledby="headingrental"  data-bs-parent="#accordionMain6">
                  <div class="card-body-chat">
                    <div class="fuel-list">
                      <ul>
                        <li>
                          <div class="input-selection">
                            <input type="radio" name="any" id="any">
                            <label for="any">Any</label>
                          </div>
                        </li>
                        <li>
                          <div class="input-selection">
                            <input type="radio" name="day" id="day" checked>
                            <label for="day">Per Day</label>
                          </div>
                        </li>
                        <li>
                          <div class="input-selection">
                            <input type="radio" name="hour" id="hour" checked>
                            <label for="hour">Per Hour</label>
                          </div>
                        </li>
                        <li>
                          <div class="input-selection">
                            <input type="radio" name="week" id="week">
                            <label for="week">Per Week</label>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="accordion" id="accordionMain06">
                <div class="card-header-new" id="headingspec">
                  <h6 class="filter-title">
                    <a href="javascript:void(0);" class="w-100 collapsed"  data-bs-toggle="collapse" data-bs-target="#collapsespec" aria-expanded="true" aria-controls="collapsespec">
                      Car Specifications
                      <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                    </a> 
                  </h6>
                </div>
                <div id="collapsespec" class="collapse" aria-labelledby="headingspec"  data-bs-parent="#accordionMain06">
                  <div class="card-body-chat">
                    <div id="checkBoxes20">
                      <div class="selectBox-cont">
                        <label class="custom_check w-100">
                          <input type="checkbox" name="username">
                          <span class="checkmark"></span> Air Conditioners
                        </label>
                        <label class="custom_check w-100">
                          <input type="checkbox" name="username">
                          <span class="checkmark"></span> Keyless
                        </label>
                        <label class="custom_check w-100">
                          <input type="checkbox" name="username">
                          <span class="checkmark"></span> Panoramic
                        </label>
                        <label class="custom_check w-100">
                          <input type="checkbox" name="username">
                          <span class="checkmark"></span> Bluetooth
                        </label>
                        <!-- View All -->
                        <div class="view-content">
                          <div class="viewall-One">
                            <label class="custom_check w-100">
                              <input type="checkbox" name="username">
                              <span class="checkmark"></span> Aux
                            </label>
                            <label class="custom_check w-100">
                              <input type="checkbox" name="username">
                              <span class="checkmark"></span> Top Window
                            </label>
                            <label class="custom_check w-100">
                              <input type="checkbox" name="username">
                              <span class="checkmark"></span> Speakers
                            </label>
                            <label class="custom_check w-100">
                              <input type="checkbox" name="username">
                              <span class="checkmark"></span> Automatic Window
                            </label>
                          </div>
                        </div>
                        <!-- /View All -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="accordion" id="accordionMain7">
                <div class="card-header-new" id="headingColor">
                  <h6 class="filter-title">
                    <a href="javascript:void(0);" class="w-100 collapsed"  data-bs-toggle="collapse" data-bs-target="#collapseColor" aria-expanded="true" aria-controls="collapseColor">
                      Colors
                      <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                    </a> 
                  </h6>
                </div>
                <div id="collapseColor" class="collapse" aria-labelledby="headingColor"  data-bs-parent="#accordionMain7">
                  <div class="card-body-chat">
                    <div class="theme-colorsset">
                      <ul>
                        <li>
                          <div class="input-themeselects">
                            <input type="radio" name="color" id="greenColor" value="red" checked>
                            <label for="greenColor" class="green-clr"></label>
                          </div>
                        </li>
                        <li>
                          <div class="input-themeselects">
                            <input type="radio" name="color" id="yellowColor" value="yellow">
                            <label for="yellowColor" class="yellow-clr"></label>
                          </div>
                        </li>
                        <li>
                          <div class="input-themeselects">
                            <input type="radio" name="color" id="brownColor" value="blue">
                            <label for="brownColor" class="brown-clr"></label>
                          </div>
                        </li>
                        <li>
                          <div class="input-themeselects">
                            <input type="radio" name="color" id="blackColor" value="green">
                            <label for="blackColor" class="black-clr"></label>
                          </div>
                        </li>
                        <li>
                          <div class="input-themeselects">
                            <input type="radio" name="color" id="redColor" value="red" checked>
                            <label for="redColor" class="red-clr"></label>
                          </div>
                        </li>
                        <li>
                          <div class="input-themeselects">
                            <input type="radio" name="color" id="grayColor" value="blue">
                            <label for="grayColor" class="gray-clr"></label>
                          </div>
                        </li>
                        <li>
                          <div class="input-themeselects">
                            <input type="radio" name="color" id="gray100Color" value="green">
                            <label for="gray100Color" class="gray100-clr"></label>
                          </div>
                        </li>
                        <li>
                          <div class="input-themeselects">
                            <input type="radio" name="color" id="blueColor" value="yellow">
                            <label for="blueColor" class="blue-clr"></label>
                          </div>
                        </li>
                        <li>
                          <div class="input-themeselects">
                            <input type="radio" name="color" id="whiteColor" value="yellow">
                            <label for="whiteColor" class="white-clr"></label>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="accordion" id="accordionMain8">
                <div class="card-header-new" id="headingThree">
                  <h6 class="filter-title">
                    <a href="javascript:void(0);" class="w-100 collapsed"  data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                      Capacity
                      <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                    </a> 
                  </h6>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree"  data-bs-parent="#accordionMain1">
                  <div class="card-body-chat">
                    <div id="checkBoxes3">
                      <div class="selectBox-cont">
                        <?php foreach ([2, 4, 5, 7] as $p): ?>
                        <label class="custom_check w-100">
                          <input type="checkbox" name="passengers[]" value="<?= $p ?>" <?= in_array((string)$p, (array)$filterPassengers) ? 'checked' : '' ?>>
                          <span class="checkmark"></span> <?= $p ?> Seater
                        </label>
                        <?php endforeach; ?>
                      </div>
                    </div> 
                  </div>
                </div>
              </div>
              <div class="accordion" id="accordionMain9">
                <div class="card-header-new" id="headingFour">
                  <h6 class="filter-title">
                    <a href="javascript:void(0);" class="w-100 collapsed"  data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                      Price
                      <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                    </a> 
                  </h6>
                </div>
                <div id="collapseFour" class="collapse" aria-labelledby="headingFour"  data-bs-parent="#accordionMain9">
                  <div class="card-body-chat">
                    <div class="filter-range">
                      <input type="text"  class="input-range">
                    </div>
                  </div>
                </div>
              </div>
              <div class="accordion" id="accordionMain4">
                <div class="card-header-new" id="headingtransmiss">
                  <h6 class="filter-title">
                    <a href="javascript:void(0);" class="w-100 collapsed"  data-bs-toggle="collapse" data-bs-target="#collapsetransmission" aria-expanded="true" aria-controls="collapsetransmission">
                      Transmission
                      <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                    </a> 
                  </h6>
                </div>
                <div id="collapsetransmission" class="collapse" aria-labelledby="headingtransmiss"  data-bs-parent="#accordionMain4">
                  <div class="card-body-chat">
                    <div class="fuel-list">
                      <ul>
                        <li>
                          <div class="input-selection">
                            <input type="radio" name="transmission" id="manual" checked>
                            <label for="manual">Manual 	</label>
                          </div>
                        </li>
                        <li>
                          <div class="input-selection">
                            <input type="radio" name="transmission" id="semi">
                            <label for="semi">Semi Automatic</label>
                          </div>
                        </li>
                        <li>
                          <div class="input-selection">
                            <input type="radio" name="transmission" id="automatic">
                            <label for="automatic">Automatic</label>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="accordion" id="accordionMain10">
                <div class="card-header-new" id="headingFive">
                  <h6 class="filter-title">
                    <a href="javascript:void(0);" class="w-100 collapsed"  data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
                      Rating
                      <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                    </a> 
                  </h6>
                </div>
                <div id="collapseFive" class="collapse" aria-labelledby="headingFive"  data-bs-parent="#accordionMain10">
                  <div class="card-body-chat">
                    <div id="checkBoxes4">
                      <div class="selectBox-cont">
                        <label class="custom_check w-100">
                          <input type="checkbox" name="category">
                          <span class="checkmark"></span>
                          <i class="fas fa-star filled"></i>
                          <i class="fas fa-star filled"></i>
                          <i class="fas fa-star filled"></i>
                          <i class="fas fa-star filled"></i>
                          <i class="fas fa-star filled"></i>
                          <span class="rating-count">5.0</span>
                        </label>
                        <label class="custom_check w-100">
                          <input type="checkbox" name="category">
                          <span class="checkmark"></span>
                          <i class="fas fa-star filled"></i>
                          <i class="fas fa-star filled"></i>
                          <i class="fas fa-star filled"></i>
                          <i class="fas fa-star filled"></i>
                          <i class="fas fa-star"></i>
                          <span class="rating-count">4.0</span>
                        </label>
                        <label class="custom_check w-100">
                          <input type="checkbox" name="category">
                          <span class="checkmark"></span>
                          <i class="fas fa-star filled"></i>
                          <i class="fas fa-star filled"></i>
                          <i class="fas fa-star filled"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <span class="rating-count">3.0</span>
                        </label>
                        <label class="custom_check w-100">
                          <input type="checkbox" name="category">
                          <span class="checkmark"></span>
                          <i class="fas fa-star filled"></i>
                          <i class="fas fa-star filled"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <span class="rating-count">2.0</span>
                        </label> 
                        <label class="custom_check w-100">
                          <input type="checkbox" name="username">
                          <span class="checkmark"></span>
                          <i class="fas fa-star filled"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                          <span class="rating-count">1.0</span>
                        </label>
                      </div>
                    </div> 
                  </div>
                </div>
              </div>
              <div class="accordion" id="accordionMain11">
                <div class="card-header-new" id="headingSix">
                  <h6 class="filter-title">
                    <a href="javascript:void(0);" class="w-100 collapsed"  data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="true" aria-controls="collapseSix">
                      Customer Recommendation
                      <span class="float-end"><i class="fa-solid fa-chevron-down"></i></span>
                    </a> 
                  </h6>
                </div>
                <div id="collapseSix" class="collapse" aria-labelledby="headingSix"  data-bs-parent="#accordionMain11">
                  <div class="card-body-chat">
                    <div id="checkBoxes5">
                      <div class="selectBox-cont">
                        <label class="custom_check w-100">
                          <input type="checkbox" name="category">
                          <span class="checkmark"></span> 70% & up
                        </label>
                        <label class="custom_check w-100">
                          <input type="checkbox" name="category">
                          <span class="checkmark"></span> 60% & up
                        </label>
                        <label class="custom_check w-100">
                          <input type="checkbox" name="category">
                          <span class="checkmark"></span> 50% & up
                        </label>
                        <label class="custom_check w-100">
                          <input type="checkbox" name="category">
                          <span class="checkmark"></span> 40% & up
                        </label>
                        <div class="viewall-Two"> 
                          <label class="custom_check w-100">
                            <input type="checkbox" name="username">
                            <span class="checkmark"></span>30% & up
                          </label>
                        </div>
                      </div>
                    </div> 
                  </div>
                </div>
              </div>
            </div>
            <button type="submit" class="d-inline-flex align-items-center justify-content-center btn w-100 btn-primary filter-btn">
              <span><i class="feather-filter me-2"></i></span>Filter results
            </button>
            <a href="listing-list.php" class="btn reset-filter w-100 mt-2">Reset Filter</a>
          </form>
        </div>

        <div class="col-xl-9 col-lg-8 col-sm-12 col-12">
          <div class="row">
            <div class="col-12">
              <?php if ($cars): foreach ($cars as $c): ?>
              <div class="listview-car mb-4">
                <div class="card">
                  <div class="blog-widget d-flex">
                    <div class="blog-img">
                      <div class="img-slider owl-carousel">
                        <?php
                          $slides = [];
                          if (!empty($c['featured_image'])) $slides[] = imgp($c['featured_image']);
                          if (!empty($imagesByCar[$c['id']])) {
                            foreach ($imagesByCar[$c['id']] as $ip) {
                              if (!in_array($ip, $slides)) $slides[] = imgp($ip);
                            }
                          }
                          if (empty($slides)) $slides[] = 'assets/img/cars/car-01.jpg';
                        ?>
                        <?php foreach ($slides as $s): ?>
                          <div class="slide-images">
                            <a href="listing-details.php?id=<?= (int)$c['id'] ?>">
                              <img src="<?= h($s) ?>" class="img-fluid" alt="<?= h($c['name']) ?>">
                            </a>
                          </div>
                        <?php endforeach; ?>
                      </div>
                      <div class="fav-item justify-content-end">
                        <span class="img-count"><i class="feather-image"></i><?= (int)$c['img_count'] ?></span>
                        <a href="javascript:void(0)" class="fav-icon">
                          <i class="feather-heart"></i>
                        </a>
                      </div>
                    </div>
                    <div class="bloglist-content w-100">
                      <div class="card-body">
                        <div class="blog-list-head d-flex">
                          <div class="blog-list-title">
                            <h3><a href="listing-details.php?id=<?= (int)$c['id'] ?>"><?= h($c['name']) ?></a></h3>
                            <h6>Category : <span><?= h($c['brand'] ?: ($c['car_type'] ?: '-')) ?></span></h6>
                          </div>
                          <div class="blog-list-rate">
                            <div class="list-rating">
                              <i class="fas fa-star filled"></i>
                              <i class="fas fa-star filled"></i>
                              <i class="fas fa-star filled"></i>
                              <i class="fas fa-star filled"></i>
                              <i class="fas fa-star"></i>
                              <span>180 Reviews</span>
                            </div>
                            <h6>$<?= number_format((float)$c['daily_price'], 2) ?><span>/ Day</span></h6>
                          </div>
                        </div>
                        <div class="listing-details-group">
                          <ul>
                            <li>
                              <span><img src="assets/img/icons/car-parts-05.svg" alt="Transmission"></span>
                              <p><?= h($c['transmission'] ?: 'Auto') ?></p>
                            </li>
                            <li>
                              <span><img src="assets/img/icons/car-parts-02.svg" alt="Odometer"></span>
                              <p><?= h(($c['odometer'] !== null && $c['odometer'] !== '') ? $c['odometer'].' KM' : '—') ?></p>
                            </li>
                            <li>
                              <span><img src="assets/img/icons/car-parts-03.svg" alt="Fuel"></span>
                              <p><?= h($c['fuel_type'] ?: 'Petrol') ?></p>
                            </li>
                            <li>
                              <span><img src="assets/img/icons/car-parts-04.svg" alt="Power"></span>
                              <p>Power</p>
                            </li>
                            <li>
                              <span><img src="assets/img/icons/car-parts-06.svg" alt="Persons"></span>
                              <p><?= h(($c['passengers'] ?: $c['seats'] ?: 4)) ?> Persons</p>
                            </li>
                            <li>
                              <span><img src="assets/img/icons/car-parts-05.svg" alt="Year"></span>
                              <p><?= h($c['year_of_car'] ?: '—') ?></p>
                            </li>
                          </ul>
                        </div>
                        <div class="blog-list-head list-head-bottom d-flex">
                          <div class="blog-list-title">
                            <div class="title-bottom">
                              <div class="car-list-icon">
                                <img src="assets/img/profiles/avatar-14.jpg" alt="user">
                              </div>
                              <div class="address-info">
                                <h6><i class="feather-map-pin"></i><?= h($c['main_location'] ?: '-') ?></h6>
                              </div>
                              <div class="list-km">
                                <span class="km-count"><img src="assets/img/icons/map-pin.svg" alt="author">3.2m</span>
                              </div>
                            </div>
                          </div>
                          <div class="listing-button">
                            <a href="listing-details.php?id=<?= (int)$c['id'] ?>" class="btn btn-order"><span><i class="feather-calendar me-2"></i></span>Rent Now</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php endforeach; else: ?>
                <div class="alert alert-info">No cars found.</div>
              <?php endif; ?>
            </div>
          </div>

          <?php if ($pages > 1): ?>
          <div class="blog-pagination">
            <nav>
              <ul class="pagination page-item justify-content-center">
                <?php
                  $prevPage = $page > 1 ? $page - 1 : 1;
                  $nextPage = $page < $pages ? $page + 1 : $pages;
                ?>
                <li class="previtem">
                  <a class="page-link" href="<?= $page > 1 ? build_qs(['page'=>$prevPage]) : '#' ?>"><i class="fas fa-regular fa-arrow-left me-2"></i> Prev</a>
                </li>
                <li class="justify-content-center pagination-center">
                  <div class="page-group">
                    <ul>
                      <?php for ($p=1;$p<=$pages;$p++): ?>
                        <li class="page-item">
                          <?php if ($p === $page): ?>
                            <a class="active page-link" href="<?= build_qs(['page'=>$p]) ?>" aria-current="page"><?= $p ?> <span class="visually-hidden">(current)</span></a>
                          <?php else: ?>
                            <a class="page-link" href="<?= build_qs(['page'=>$p]) ?>"><?= $p ?></a>
                          <?php endif; ?>
                        </li>
                      <?php endfor; ?>
                    </ul>
                  </div>
                </li>
                <li class="nextlink">
                  <a class="page-link" href="<?= $page < $pages ? build_qs(['page'=>$nextPage]) : '#' ?>">Next <i class="fas fa-regular fa-arrow-right ms-2"></i></a>
                </li>
              </ul>
            </nav>
          </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </section>

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
                      <a href="about-us.html">Our Company</a>
                    </li>
                    <li>
                      <a href="javascript:void(0);">Shop Toyota</a>
                    </li>
                    <li>
                      <a href="javascript:void(0);">Dreamsrentals USA</a>
                    </li>
                    <li>
                      <a href="javascript:void(0);">Dreamsrentals Worldwide</a>
                    </li>
                    <li>
                      <a href="javascript:void(0);">Dreamsrental Category</a>
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
                      <a href="javascript:void(0);">All  Vehicles</a>
                    </li>
                    <li>
                      <a href="javascript:void(0);">SUVs</a>
                    </li>
                    <li>
                      <a href="javascript:void(0);">Trucks</a>
                    </li>
                    <li>
                      <a href="javascript:void(0);">Cars</a>
                    </li>
                    <li>
                      <a href="javascript:void(0);">Crossovers</a>
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
                      <a href="javascript:void(0);">My Account</a>
                    </li>
                    <li>
                      <a href="javascript:void(0);">Champaigns</a>
                    </li>
                    <li>
                      <a href="javascript:void(0);">Dreamsrental Dealers</a>
                    </li>
                    <li>
                      <a href="javascript:void(0);">Deals and Incentive</a>
                    </li>
                    <li>
                      <a href="javascript:void(0);">Financial Services</a>
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
                  <span>
                    <i class="feather-phone-call"></i>
                  </span>
                  <div class="addr-info">
                    <a href="tel:+1(888)7601940">+ 1 (888) 760 1940</a>
                  </div>
                </div>
                <div class="footer-address">
                  <span>
                    <i class="feather-mail"></i>
                  </span>
                  <div class="addr-info">
                    <a href="mailto:support@example.com">support@example.com</a>
                  </div>
                </div>
                <div class="update-form">
                  <form action="#">
                    <span>
                      <i class="feather-mail"></i>
                    </span>
                    <input type="email" class="form-control" placeholder="Enter You Email Here">
                    <button type="submit" class="btn btn-subscribe">
                      <span>
                        <i class="feather-send"></i>
                      </span>
                    </button>
                  </form>
                </div>
              </div>
              <div class="footer-social-widget">
                <ul class="nav-social">
                  <li>
                    <a href="javascript:void(0);">
                      <i class="fa-brands fa-facebook-f fa-facebook fi-icon"></i>
                    </a>
                  </li>
                  <li>
                    <a href="javascript:void(0);">
                      <i class="fab fa-instagram fi-icon"></i>
                    </a>
                  </li>
                  <li>
                    <a href="javascript:void(0);">
                      <i class="fab fa-behance fi-icon"></i>
                    </a>
                  </li>
                  <li>
                    <a href="javascript:void(0);">
                      <i class="fab fa-twitter fi-icon"></i>
                    </a>
                  </li>
                  <li>
                    <a href="javascript:void(0);">
                      <i class="fab fa-linkedin fi-icon"></i>
                    </a>
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
                    <li>
                      <a href="javascript:void(0);">
                        <img class="img-fluid" src="assets/img/icons/paypal.svg" alt="Paypal">
                      </a>
                    </li>
                    <li>
                      <a href="javascript:void(0);">
                        <img class="img-fluid" src="assets/img/icons/visa.svg" alt="Visa">
                      </a>
                    </li>
                    <li>
                      <a href="javascript:void(0);">
                        <img class="img-fluid" src="assets/img/icons/master.svg" alt="Master">
                      </a>
                    </li>
                    <li>
                      <a href="javascript:void(0);">
                        <img class="img-fluid" src="assets/img/icons/applegpay.svg" alt="applegpay">
                      </a>
                    </li>
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

<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/plugins/aos/aos.js"></script>
<script src="assets/plugins/select2/js/select2.min.js"></script>
<script src="assets/js/backToTop.js"></script>
<script src="assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js"></script>
<script src="assets/plugins/ion-rangeslider/js/custom-rangeslider.js"></script>
<script src="assets/plugins/theia-sticky-sidebar/ResizeSensor.js"></script>
<script src="assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js"></script>
<script src="assets/plugins/moment/moment.min.js"></script>
<script src="assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="assets/js/script.js"></script>
<script>AOS.init();</script>
<script>
// Show and Sort selects change handler
document.addEventListener('DOMContentLoaded', function(){
  var show = document.getElementById('showSelect');
  var sort = document.getElementById('sortSelect');
  
  if(show){ 
    show.addEventListener('change', function(){ 
      var params = new URLSearchParams(window.location.search);
      params.set('per', this.value);
      params.set('page', '1'); // Reset to first page
      window.location = window.location.pathname + '?' + params.toString();
    }); 
  }
  
  if(sort){ 
    sort.addEventListener('change', function(){ 
      var params = new URLSearchParams(window.location.search);
      params.set('sort', this.value);
      params.set('page', '1'); // Reset to first page
      window.location = window.location.pathname + '?' + params.toString();
    }); 
  }
});
</script>
</body>
</html>
