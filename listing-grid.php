<?php
require_once __DIR__ . '/config/database.php';
function h($v){return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');}
function imgp($p){ if(!$p) return ''; if(strpos($p, '../')===0) return substr($p, 3); return $p; }

$perPage = 9;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;
$q = trim($_GET['q'] ?? '');

// Build WHERE
$where = "(c.status IS NULL OR TRIM(c.status)='' OR LOWER(TRIM(c.status))='active')";
$params = [];
if ($q !== '') {
  $where .= " AND (c.name LIKE :q OR c.brand LIKE :q OR c.model LIKE :q OR c.main_location LIKE :q)";
  $params[':q'] = "%$q%";
}

// Count total
$countSql = "SELECT COUNT(*) FROM tblcars c WHERE $where";
$cstmt = $pdo->prepare($countSql);
$cstmt->execute($params);
$total = (int)$cstmt->fetchColumn();

// Fetch page rows with image count
$sql = "SELECT c.id, c.name, c.brand, c.model, c.car_type, c.featured_image, c.main_location, c.daily_price,
               COUNT(ci.id) AS img_count
        FROM tblcars c
        LEFT JOIN car_images ci ON ci.car_id = c.id
        WHERE $where
        GROUP BY c.id
        ORDER BY c.created_at DESC
        LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
foreach ($params as $k=>$v) { $stmt->bindValue($k, $v); }
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pages = max(1, (int)ceil($total / $perPage));
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
                <li class="active"><a href="listing-grid.php">Listing Grid</a></li>
                <li><a href="#">Listing List</a></li>
                <li><a href="#">Listing With Map</a></li>
                <li><a href="#">Listing Details</a></li>
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
    <div class="container"><div class="row align-items-center text-center"><div class="col-md-12 col-12"><h2 class="breadcrumb-title">Car Listings</h2>
      <nav aria-label="breadcrumb" class="page-breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="index-2.php">Home</a></li><li class="breadcrumb-item active">Car Listings</li></ol></nav>
    </div></div></div>
  </div>

  <section class="section car-listing pt-0">
    <div class="container">
      <div class="row">
        <div class="col-xl-3 col-lg-4 col-sm-12 col-12 theiaStickySidebar">
          <form action="listing-grid.php" method="get" autocomplete="off" class="sidebar-form">
            <div class="sidebar-heading"><h3>What Are You Looking For</h3></div>
            <div class="product-search">
              <div class="form-custom"><input type="text" name="q" class="form-control" value="<?= h($_GET['q'] ?? '') ?>" placeholder="Search by name or brand"><span><img src="assets/img/icons/search.svg" alt="img"></span></div>
            </div>
            <button type="submit" class="btn w-100 btn-primary filter-btn"><span><i class="feather-filter me-2"></i></span>Filter results</button>
            <a href="listing-grid.php" class="reset-filter">Reset Filter</a>
          </form>
        </div>
        <div class="col-lg-9">
          <div class="row">
      <?php if ($cars): foreach ($cars as $c): ?>
            <div class="col-xxl-4 col-lg-6 col-md-6 col-12">
              <div class="listing-item">
                <div class="listing-img">
                  <a href="listing-details.php?id=<?= (int)$c['id'] ?>">
        <img src="<?= h(imgp($c['featured_image']) ?: 'assets/img/cars/car-01.jpg') ?>" class="img-fluid" alt="car">
                  </a>
                  <div class="fav-item justify-content-end">
                    <span class="img-count"><i class="feather-image"></i><?= (int)$c['img_count'] ?></span>
                    <a href="javascript:void(0)" class="fav-icon"><i class="feather-heart"></i></a>
                  </div>
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
                  <div class="listing-button"><a href="listing-details.php?id=<?= (int)$c['id'] ?>" class="btn btn-order"><span><i class="feather-calendar me-2"></i></span>Rent Now</a></div>
                </div>
              </div>
            </div>
            <?php endforeach; else: ?>
              <div class="col-12"><div class="alert alert-info">No cars found.</div></div>
            <?php endif; ?>
          </div>

          <?php if ($pages > 1): ?>
          <div class="blog-pagination">
            <nav>
              <ul class="pagination page-item justify-content-center">
                <?php
                  $baseParams = [];
                  if ($q !== '') $baseParams['q'] = $q;
                  for ($p=1;$p<=$pages;$p++): $active = $p===$page ? 'active' : '';
                    $qs = http_build_query(array_merge($baseParams, ['page'=>$p]));
                ?>
                  <li class="page-item <?= $active ?>"><a class="page-link" href="?<?= h($qs) ?>"><?= $p ?></a></li>
                <?php endfor; ?>
              </ul>
            </nav>
          </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </section>

  <footer class="footer"><div class="footer-bottom"><div class="container"><div class="copyright"><div class="row align-items-center"><div class="col-md-12 text-center">&copy; <?= date('Y') ?> Dreams Rent</div></div></div></div></div></footer>

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
</body>
</html>
