<?php
session_start();
require_once '../config/database.php';

function h($v){return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  $_SESSION['error_message'] = 'Invalid car ID';
  header('Location: cars.php');
  exit;
}

// Fetch main car
$car = null;
try {
  $stmt = $pdo->prepare("SELECT * FROM tblcars WHERE id = :id");
  $stmt->execute([':id' => $id]);
  $car = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$car) {
    $_SESSION['error_message'] = 'Car not found';
    header('Location: cars.php');
    exit;
  }
} catch (Throwable $e) {
  $_SESSION['error_message'] = 'Failed to load car: ' . $e->getMessage();
  header('Location: cars.php');
  exit;
}

// Fetch gallery images
$images = [];
try {
  $g = $pdo->prepare('SELECT image_path FROM car_images WHERE car_id = :id ORDER BY id ASC');
  $g->execute([':id' => $id]);
  $images = $g->fetchAll(PDO::FETCH_COLUMN);
} catch (Throwable $e) { /* ignore */ }

// Fetch damages
$damages = [];
try {
  $d = $pdo->prepare('SELECT location, `type`, description, date_added FROM car_damages WHERE car_id = :id ORDER BY id DESC');
  $d->execute([':id' => $id]);
  $damages = $d->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) { /* ignore */ }

// Fetch insurance (latest if many)
$insurance = null;
try {
  $ins = $pdo->prepare('SELECT insurance_name, price, benefits FROM car_insurance WHERE car_id = :id ORDER BY id DESC LIMIT 1');
  $ins->execute([':id' => $id]);
  $insurance = $ins->fetch(PDO::FETCH_ASSOC);
} catch (Throwable $e) { /* ignore */ }

// Decode extras/features
// Decode helper with double-decode fallback
function decode_array_field($value) {
  if ($value === null || $value === '') return [];
  $first = json_decode($value, true);
  if (is_array($first)) return $first;
  // If value was encoded twice or escaped, try un-escaping
  $stripped = trim($value, "\"'");
  $stripped = str_replace(['\\"','\\\\'], ['"','\\'], $stripped);
  $second = json_decode($stripped, true);
  return is_array($second) ? $second : [];
}

$features = decode_array_field($car['features_amenities'] ?? null);
$extras = decode_array_field($car['extra_services'] ?? null);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <title>Car Details</title>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/plugins/tabler-icons/tabler-icons.min.css">
  <link rel="stylesheet" href="assets/plugins/fancybox/jquery.fancybox.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/js/theme-script.js"></script>
  <style>
    .car-cover { width: 100%; max-height: 280px; object-fit: cover; border-radius: 8px; }
    .car-thumb { width: 72px; height: 72px; object-fit: cover; border-radius: 6px; }
    .chip { display:inline-block; padding: 2px 8px; border-radius: 12px; font-size: 12px; background: #f6f6f7; margin: 2px; }
  </style>
</head>
<body>
<div class="main-wrapper">
  <div class="page-wrapper">
    <div class="content me-4">
      <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
          <h4 class="mb-1">Car Details</h4>
          <p class="text-muted mb-0"><a href="cars.php" class="text-decoration-none">Cars</a> / <?= h($car['name'] ?: 'Untitled') ?></p>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center flex-wrap ">
          <div class="mb-2">
            <a href="cars.php" class="btn btn-light d-flex align-items-center"><i class="ti ti-chevron-left me-1"></i>Back to List</a>
          </div>
        </div>
      </div>

      <!-- Summary card -->
      <div class="card mb-3">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-lg-4">
              <?php $img = $car['featured_image'] ?: 'assets/img/car/car-02.jpg'; ?>
              <img src="<?= h($img) ?>" alt="Car" class="car-cover w-100">
            </div>
            <div class="col-lg-8">
              <h3 class="mb-1"><?= h($car['name'] ?: 'Untitled') ?></h3>
              <div class="text-muted mb-2">Type: <?= h($car['car_type'] ?: '-') ?> · Brand: <?= h($car['brand'] ?: '-') ?> · Model: <?= h($car['model'] ?: '-') ?></div>
              <div class="mb-2">Location: <strong><?= h($car['main_location'] ?: '-') ?></strong></div>
              <div class="mb-2">Daily Price: <strong>$<?= number_format((float)$car['daily_price'], 2) ?></strong></div>
              <div class="mb-2">Status: 
                <?php $status = $car['status'] ?: 'Inactive'; $cls = ($status==='Active'?'text-success':($status==='Inactive'?'text-danger':'text-info')); ?>
                <span class="badge badge-dark-transparent"><i class="ti ti-point-filled <?= $cls ?> me-1"></i><?= h($status) ?></span>
              </div>
              <?php if (!empty($car['description'])): ?>
                <p class="mb-0 mt-2"><?= nl2br(h($car['description'])) ?></p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Gallery -->
      <div class="card mb-3">
        <div class="card-header"><h5 class="mb-0">Gallery</h5></div>
        <div class="card-body">
          <?php if (empty($images)): ?>
            <p class="text-muted mb-0">No gallery images.</p>
          <?php else: ?>
            <div class="d-flex flex-wrap gap-2">
              <?php foreach ($images as $gp): ?>
                <a href="<?= h($gp) ?>" data-fancybox="gallery">
                  <img src="<?= h($gp) ?>" class="car-thumb" alt="Gallery">
                </a>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Specifications -->
      <div class="card mb-3">
        <div class="card-header"><h5 class="mb-0">Specifications</h5></div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-3"><div class="text-muted">Category</div><div><?= h($car['category'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">Fuel</div><div><?= h($car['fuel_type'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">Transmission</div><div><?= h($car['transmission'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">Color</div><div><?= h($car['color'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">Year</div><div><?= h($car['year_of_car'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">Passengers</div><div><?= h($car['passengers'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">Seats</div><div><?= h($car['seats'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">Doors</div><div><?= h($car['doors'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">Air Bags</div><div><?= h($car['air_bags'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">Odometer</div><div><?= h($car['odometer'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">Mileage</div><div><?= h($car['mileage'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">VIN</div><div><?= h($car['vin_number'] ?: '-') ?></div></div>
            <div class="col-md-3"><div class="text-muted">Plate</div><div><?= h($car['plate_number'] ?: '-') ?></div></div>
          </div>
        </div>
      </div>

      <!-- Pricing -->
      <div class="card mb-3">
        <div class="card-header"><h5 class="mb-0">Pricing</h5></div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-3"><div class="text-muted">Daily Price</div><div>$<?= number_format((float)$car['daily_price'], 2) ?></div></div>
            <div class="col-md-3"><div class="text-muted">Base KM/Day</div><div><?= h($car['base_kilometers_per_day'] ?: 0) ?></div></div>
            <div class="col-md-3"><div class="text-muted">Extra KM Price</div><div>$<?= number_format((float)$car['kilometers_extra_price'], 2) ?></div></div>
            <div class="col-md-3"><div class="text-muted">Unlimited KM</div><div><?= ($car['unlimited_kilometers'] ? 'Yes' : 'No') ?></div></div>
          </div>
        </div>
      </div>

      <!-- Features & Extras -->
      <div class="card mb-3">
        <div class="card-header"><h5 class="mb-0">Features & Amenities</h5></div>
        <div class="card-body">
          <?php if (empty($features)): ?>
            <p class="text-muted mb-0">No features selected.</p>
          <?php else: foreach ($features as $f): ?>
            <span class="chip"><?= h(str_replace('_', ' ', $f)) ?></span>
          <?php endforeach; endif; ?>
        </div>
      </div>

      <div class="card mb-3">
        <div class="card-header"><h5 class="mb-0">Extra Services</h5></div>
        <div class="card-body">
          <?php if (empty($extras)): ?>
            <p class="text-muted mb-0">No extra services selected.</p>
          <?php else: foreach ($extras as $ex): ?>
            <span class="chip"><?= h(str_replace('_', ' ', $ex)) ?></span>
          <?php endforeach; endif; ?>
        </div>
      </div>

      <!-- Insurance -->
      <div class="card mb-3">
        <div class="card-header"><h5 class="mb-0">Insurance</h5></div>
        <div class="card-body">
          <?php if (!$insurance): ?>
            <p class="text-muted mb-0">No insurance selected.</p>
          <?php else: ?>
            <div class="row g-2">
              <div class="col-md-4"><div class="text-muted">Name</div><div><?= h($insurance['insurance_name']) ?></div></div>
              <div class="col-md-4"><div class="text-muted">Price</div><div>$<?= number_format((float)$insurance['price'], 2) ?></div></div>
              <div class="col-md-4"><div class="text-muted">Benefits</div><div><?= h($insurance['benefits'] ?? '-') ?></div></div>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Damages -->
      <div class="card mb-4">
        <div class="card-header"><h5 class="mb-0">Damages</h5></div>
        <div class="card-body">
          <?php if (empty($damages)): ?>
            <p class="text-muted mb-0">No damages recorded.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>Location</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Date</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($damages as $dg): ?>
                    <tr>
                      <td><?= h($dg['location']) ?></td>
                      <td><?= h($dg['type']) ?></td>
                      <td><?= h($dg['description'] ?: '-') ?></td>
                      <td><?= h($dg['date_added']) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>

    </div>

    <div class="footer d-sm-flex align-items-center justify-content-between bg-white p-3">
      <p class="mb-0">
        <a href="#">Privacy Policy</a>
        <a href="#" class="ms-4">Terms of Use</a>
      </p>
      <p>&copy; <?= date('Y') ?> RentalHub</p>
    </div>
  </div>
</div>

<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/js/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/fancybox/jquery.fancybox.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>