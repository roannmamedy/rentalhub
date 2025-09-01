<?php
session_start();
require_once '../config/database.php';

function h($v){return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');}

// Fetch cars with basic aggregates
$cars = [];
try {
  $sql = "SELECT c.id, c.name, c.car_type, c.featured_image, c.main_location, c.daily_price, c.status, c.created_at,
                   (SELECT COUNT(*) FROM car_damages d WHERE d.car_id = c.id) AS damage_count
            FROM tblcars c
            ORDER BY c.created_at DESC, c.id DESC";
    $stmt = $pdo->query($sql);
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    $_SESSION['error_message'] = 'Failed to load cars: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Cars</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/plugins/tabler-icons/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/plugins/datatables/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/theme-script.js"></script>
    <style>
      .car-thumb { width: 48px; height: 48px; object-fit: cover; border-radius: 6px; }
    </style>
  </head>
  <body>
  <div class="main-wrapper">
    <div class="page-wrapper">
      <div class="content me-4">
        <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
          <div class="my-auto mb-2">
            <h4 class="mb-1">All Cars</h4>
          </div>
          <div class="d-flex my-xl-auto right-content align-items-center flex-wrap ">
            <div class="mb-2">
              <a href="add-car.php" class="btn btn-primary d-flex align-items-center"><i class="ti ti-plus me-2"></i>Add New Car</a>
            </div>
          </div>
        </div>

        <?php if(isset($_SESSION['success_message'])): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= h($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>
        <?php if(isset($_SESSION['error_message'])): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= h($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

        <div class="table-responsive">
          <table class="table datatable">
            <thead>
              <tr>
                <th class="no-sort">
                  <div class="form-check form-check-md"></div>
                </th>
                <th>CAR / TYPE</th>
                <th>BASE LOCATION</th>
                <th>PRICE (PER DAY)</th>
                <th>DAMAGES</th>
                <th>CREATED DATE</th>
                <th>STATUS</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php if(empty($cars)): ?>
                <tr><td colspan="9" class="text-center text-muted">No cars found.</td></tr>
              <?php else: foreach($cars as $c): ?>
                <tr>
                  <td><div class="form-check form-check-md"></div></td>
                  <td>
                    <div class="d-flex align-items-center">
                      <?php
                        $img = $c['featured_image'] ?: 'assets/img/car/car-02.jpg';
                      ?>
                      <img src="<?= h($img) ?>" alt="Car" class="car-thumb me-2">
                      <div>
                        <div class="fw-semibold"><a href="car-details.php?id=<?= (int)$c['id'] ?>" class="text-reset text-decoration-none"><?= h($c['name'] ?: 'Untitled') ?></a></div>
                        <div class="text-muted small">Type: <?= h($c['car_type'] ?: '-') ?> · #<?= (int)$c['id'] ?></div>
                      </div>
                    </div>
                  </td>
                  <td><?= h($c['main_location'] ?: '-') ?></td>
                  <td><p class="fs-14 fw-semibold text-gray-9">$<?= number_format((float)$c['daily_price'], 2) ?></p></td>
                  <td><p class="text-gray-9"><?= (int)($c['damage_count'] ?? 0) ?></p></td>
                  <td>
                    <?php $ts = strtotime($c['created_at'] ?? ''); ?>
                    <h6 class="fs-14 fw-normal mb-0"><?= $ts ? date('d M Y', $ts) : '-' ?></h6>
                    <p class="fs-13 mb-0"><?= $ts ? date('h:i A', $ts) : '' ?></p>
                  </td>
                  <td>
                    <?php
                      $status = $c['status'] ?: 'Inactive';
                      $cls = ($status === 'Active') ? 'text-success' : (($status === 'Inactive') ? 'text-danger' : 'text-info');
                    ?>
                    <span class="badge badge-dark-transparent"><i class="ti ti-point-filled <?= $cls ?> me-1"></i><?= h($status) ?></span>
                  </td>
                  <td>
                    <div class="dropdown">
                      <a href="#" class="btn btn-white btn-sm" data-bs-toggle="dropdown"><i class="ti ti-dots"></i></a>
                      <div class="dropdown-menu dropdown-menu-end p-2">
                        <a class="dropdown-item" href="car-details.php?id=<?= (int)$c['id'] ?>">View</a>
                      </div>
                    </div>
                  </td>
                </tr>
              <?php endforeach; endif; ?>
            </tbody>
          </table>
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
  <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="assets/plugins/datatables/dataTables.bootstrap5.min.js"></script>
  <script src="assets/plugins/select2/js/select2.min.js"></script>
  <script src="assets/js/script.js"></script>
  </body>
  </html>
