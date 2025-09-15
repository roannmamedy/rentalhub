<?php
session_start();
require_once '../config/database.php';

function h($v){return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');}
function decode_array_field($value) {
  if ($value === null || $value === '') return [];
  $first = json_decode($value, true);
  if (is_array($first)) return $first;
  $stripped = trim($value, "\"'");
  $stripped = str_replace(['\\"','\\\\'], ['"','\\'], $stripped);
  $second = json_decode($stripped, true);
  return is_array($second) ? $second : [];
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if($id <= 0){
  $_SESSION['error_message'] = 'Invalid car ID';
  header('Location: cars.php');
  exit;
}

try{
  $stmt = $pdo->prepare('SELECT * FROM tblcars WHERE id = :id');
  $stmt->execute([':id'=>$id]);
  $car = $stmt->fetch(PDO::FETCH_ASSOC);
  if(!$car){
    $_SESSION['error_message'] = 'Car not found';
    header('Location: cars.php');
    exit;
  }
} catch (Throwable $e){
  $_SESSION['error_message'] = 'Failed to load car: '.$e->getMessage();
  header('Location: cars.php');
  exit;
}

$features = decode_array_field($car['features_amenities'] ?? null);
$extras = decode_array_field($car['extra_services'] ?? null);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
  <title>Edit Car - <?= h($car['name'] ?: 'Untitled') ?></title>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/plugins/tabler-icons/tabler-icons.min.css">
  <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="assets/plugins/fancybox/jquery.fancybox.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/js/theme-script.js"></script>
</head>
<body>
<div class="main-wrapper">
  <div class="header"><div class="main-header">
    <div class="header-left"><a href="index.html" class="logo"><img src="assets/img/logo.svg" alt="Logo"></a><a href="index.html" class="dark-logo"><img src="assets/img/logo-white.svg" alt="Logo"></a></div>
    <a id="mobile_btn" class="mobile_btn" href="#sidebar"><span class="bar-icon"><span></span><span></span><span></span></span></a>
  </div></div>

  <div class="sidebar" id="sidebar">
    <div class="sidebar-logo"><a href="index.html" class="logo logo-normal"><img src="assets/img/logo.svg" alt="Logo"></a><a href="index.html" class="logo-small"><img src="assets/img/logo-small.svg" alt="Logo"></a><a href="index.html" class="dark-logo"><img src="assets/img/logo-white.svg" alt="Logo"></a></div>
    <div class="sidebar-inner slimscroll"><div id="sidebar-menu" class="sidebar-menu">
      <ul><li class="menu-title"><span>RENTALS</span></li><li><ul><li class="active"><a href="cars.php"><i class="ti ti-car"></i><span>Cars</span></a></li></ul></li></ul>
    </div></div>
  </div>

  <div class="page-wrapper">
    <div class="content me-0">
      <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <a href="car-details.php?id=<?= (int)$car['id'] ?>" class="d-inline-flex align-items-center fw-medium"><i class="ti ti-arrow-left me-1"></i>Back to Details</a>
        <h4 class="mb-0">Edit Car: <?= h($car['name'] ?: 'Untitled') ?></h4>
      </div>

      <?php if(isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= h($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <form id="editCarForm" action="process-edit-car.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= (int)$car['id'] ?>">
        <div class="card mb-3"><div class="card-body">
          <h5 class="mb-3">Basic Info</h5>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Name <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control" value="<?= h($car['name']) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Status <span class="text-danger">*</span></label>
              <select name="status" class="form-select" required>
                <?php $st = $car['status'] ?: 'Active'; $opts=['Active','Inactive','Maintenance','Rented']; foreach($opts as $o): ?>
                  <option value="<?= h($o) ?>" <?= $st===$o?'selected':'' ?>><?= h($o) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea name="description" class="form-control" rows="3"><?= h($car['description'] ?? '') ?></textarea>
            </div>
          </div>
        </div></div>

        <div class="card mb-3"><div class="card-body">
          <h5 class="mb-3">Images</h5>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Featured Image (leave blank to keep)</label>
              <input type="file" name="featured_image" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp">
              <?php if(!empty($car['featured_image'])): ?>
                <div class="mt-2"><img src="<?= h($car['featured_image']) ?>" alt="Current" style="max-height:80px;border-radius:4px"></div>
              <?php endif; ?>
            </div>
            <div class="col-md-6">
              <label class="form-label">Add Gallery Images</label>
              <input type="file" name="gallery_images[]" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp" multiple>
            </div>
          </div>
        </div></div>

        <div class="card mb-3"><div class="card-body">
          <h5 class="mb-3">Specifications</h5>
          <div class="row g-3">
            <div class="col-md-3">
              <label class="form-label">Car Type</label>
              <select name="car_type" class="form-select">
                <?php $types=['','Sedan','Hatchback','SUV','Coupes']; $cur=$car['car_type']??''; foreach($types as $t): ?>
                  <option value="<?= h($t) ?>" <?= $cur===$t?'selected':'' ?>><?= $t?:'Select' ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Brand</label>
              <select name="brand" class="form-select">
                <?php $brands=['','Toyota','Audi','Lamborghini','BMW']; $cur=$car['brand']??''; foreach($brands as $t): ?>
                  <option value="<?= h($t) ?>" <?= $cur===$t?'selected':'' ?>><?= $t?:'Select' ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Model</label>
              <select name="model" class="form-select">
                <?php $models=['','Urban Cruiser','Fortuner','V8','Huracan']; $cur=$car['model']??''; foreach($models as $t): ?>
                  <option value="<?= h($t) ?>" <?= $cur===$t?'selected':'' ?>><?= $t?:'Select' ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Category</label>
              <select name="category" class="form-select">
                <?php $cats=['','Car','Bike','Truck']; $cur=$car['category']??''; foreach($cats as $t): ?>
                  <option value="<?= h($t) ?>" <?= $cur===$t?'selected':'' ?>><?= $t?:'Select' ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3"><label class="form-label">Plate Number</label><input type="text" name="plate_number" class="form-control" value="<?= h($car['plate_number']) ?>"></div>
            <div class="col-md-3"><label class="form-label">VIN Number</label><input type="text" name="vin_number" class="form-control" value="<?= h($car['vin_number']) ?>"></div>
            <div class="col-md-3">
              <label class="form-label">Main Location</label>
              <select name="main_location" class="form-select">
                <?php $locs=['','Johnson Dealer Zone','Miller Auto Trade Zone','Thompson Dealer Parking']; $cur=$car['main_location']??''; foreach($locs as $t): ?>
                  <option value="<?= h($t) ?>" <?= $cur===$t?'selected':'' ?>><?= $t?:'Select' ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label">Fuel</label>
              <select name="fuel_type" class="form-select">
                <?php $fuels=['','Petrol','Diesel','Electric']; $cur=$car['fuel_type']??''; foreach($fuels as $t): ?>
                  <option value="<?= h($t) ?>" <?= $cur===$t?'selected':'' ?>><?= $t?:'Select' ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3"><label class="form-label">Odometer</label><input type="text" name="odometer" class="form-control" value="<?= h($car['odometer']) ?>"></div>
            <div class="col-md-3"><label class="form-label">Color</label><input type="text" name="color" class="form-control" value="<?= h($car['color']) ?>"></div>
            <div class="col-md-3"><label class="form-label">Year of Car</label><input type="number" name="year_of_car" class="form-control" value="<?= (int)($car['year_of_car']??0) ?>" min="1900" max="2035"></div>
            <div class="col-md-3">
              <label class="form-label">Transmission</label>
              <select name="transmission" class="form-select">
                <?php $trs=['','Manual','Automatic','Semi Automatic']; $cur=$car['transmission']??''; foreach($trs as $t): ?>
                  <option value="<?= h($t) ?>" <?= $cur===$t?'selected':'' ?>><?= $t?:'Select' ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3"><label class="form-label">Mileage</label><input type="text" name="mileage" class="form-control" value="<?= h($car['mileage']) ?>"></div>
            <div class="col-md-3"><label class="form-label">Passengers</label><input type="number" name="passengers" class="form-control" value="<?= (int)($car['passengers']??0) ?>" min="0" max="20"></div>
            <div class="col-md-3"><label class="form-label">Seats</label><input type="text" name="seats" class="form-control" value="<?= h($car['seats']) ?>"></div>
            <div class="col-md-3"><label class="form-label">Doors</label><input type="text" name="doors" class="form-control" value="<?= h($car['doors']) ?>"></div>
            <div class="col-md-3"><label class="form-label">Air Bags</label><input type="number" name="air_bags" class="form-control" value="<?= (int)($car['air_bags']??0) ?>" min="0" max="20"></div>
          </div>
        </div></div>

        <div class="card mb-3"><div class="card-body">
          <h5 class="mb-3">Pricing</h5>
          <div class="row g-3">
            <div class="col-md-4"><label class="form-label">Daily Price</label><input type="number" step="0.01" name="daily_price" class="form-control" value="<?= h($car['daily_price']) ?>"></div>
            <div class="col-md-4"><label class="form-label">Base Kilometers (Per Day)</label><input type="number" name="base_kilometers_per_day" class="form-control" value="<?= (int)($car['base_kilometers_per_day']??0) ?>" min="0"></div>
            <div class="col-md-4"><label class="form-label">Kilometers Extra Price</label><input type="number" step="0.01" name="kilometers_extra_price" class="form-control" value="<?= h($car['kilometers_extra_price']) ?>" min="0"></div>
            <div class="col-md-4 form-check mt-2 ms-2">
              <input class="form-check-input" type="checkbox" id="unlimited" name="unlimited_kilometers" <?= !empty($car['unlimited_kilometers'])?'checked':'' ?>>
              <label class="form-check-label" for="unlimited">Unlimited Kilometers</label>
            </div>
          </div>
        </div></div>

        <div class="card mb-3"><div class="card-body">
          <h5 class="mb-3">Features & Amenities</h5>
          <div class="row">
            <?php $allF=['air_condition','climate_control','climate_control_two_zones','luxury_climate_control','sunroof','panoramic_sunroof','moonroof','push_button_start','keyless_access','rear_parking_sensors','parking_sensors','built_in_sat_nav','mobile_phone_technology','bluetooth','usb','qi_wireless_charging','audio_ipod','cruise_control','adaptive_cruise_control','apple_carplay','android_auto','forward_collision_warning','lane_departure_warning','automatic_emergency_braking','active_parking_assist','automatic_high_beams','adaptive_headlights','360_degree_camera','rearview_camera','towing_hook','leather_interior','fabric_interior'];
            foreach($allF as $val): $checked=in_array($val,$features,true)?'checked':''; ?>
              <div class="col-md-4"><div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="features[]" value="<?= h($val) ?>" id="f_<?= h($val) ?>" <?= $checked ?>><label class="form-check-label" for="f_<?= h($val) ?>"><?= str_replace('_',' ', $val) ?></label></div></div>
            <?php endforeach; ?>
          </div>
        </div></div>

        <div class="card mb-3"><div class="card-body">
          <h5 class="mb-3">Extra Services</h5>
          <div class="row">
            <?php $allS=['navigation','wifi_hotspot','child_safety_seats','fuel_pre_purchase','roadside_assistance','satellite_radio','usb_charger','express_checkin_checkout','toll_pass','insurance','dash_cam'];
            foreach($allS as $val): $checked=in_array($val,$extras,true)?'checked':''; ?>
              <div class="col-md-4"><div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="extra_services[]" value="<?= h($val) ?>" id="s_<?= h($val) ?>" <?= $checked ?>><label class="form-check-label" for="s_<?= h($val) ?>"><?= str_replace('_',' ', $val) ?></label></div></div>
            <?php endforeach; ?>
          </div>
        </div></div>

        <div class="d-flex justify-content-end gap-2 mb-4">
          <a href="car-details.php?id=<?= (int)$car['id'] ?>" class="btn btn-light d-flex align-items-center"><i class="ti ti-chevron-left me-1"></i>Cancel</a>
          <button type="submit" class="btn btn-primary d-flex align-items-center" id="saveBtn">Save Changes<i class="ti ti-chevron-right ms-1"></i></button>
        </div>
      </form>
    </div>
    <div class="footer d-sm-flex align-items-center justify-content-between bg-white p-3">
      <p class="mb-0"><a href="#">Privacy Policy</a><a href="#" class="ms-4">Terms of Use</a></p>
      <p>&copy; <?= date('Y') ?> RentalHub</p>
    </div>
  </div>
</div>

<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/plugins/select2/js/select2.min.js"></script>
<script src="assets/plugins/fancybox/jquery.fancybox.min.js"></script>
<script src="assets/js/script.js"></script>
<script>
  // Submit via AJAX like add-car
  $(function(){
    $('#editCarForm').on('submit', function(e){
      e.preventDefault();
      const fd = new FormData(this);
      // Flatten features/services like add-car.php (backend accepts both array or JSON; we align with JSON)
      const features=[]; $(this).find('input[name="features[]"]:checked').each(function(){features.push(this.value)});
      const services=[]; $(this).find('input[name="extra_services[]"]:checked').each(function(){services.push(this.value)});
      fd.delete('features[]'); fd.delete('extra_services[]');
      fd.append('features_amenities', JSON.stringify(features));
      fd.append('extra_services', JSON.stringify(services));
      fd.append('ajax_request','1');
      const $btn = $('#saveBtn'); $btn.prop('disabled', true);
      $.ajax({url: $(this).attr('action'), method:'POST', data: fd, processData:false, contentType:false, dataType:'json'})
      .done(function(res){
        if(res && res.success){
          window.location.href = 'car-details.php?id='+<?= (int)$car['id'] ?>;
        } else {
          alert((res && res.message) || 'Failed to save changes');
        }
      })
      .fail(function(){ alert('Error occurred while saving changes'); })
      .always(function(){ $btn.prop('disabled', false); });
    });
  });
</script>
</body>
</html>
