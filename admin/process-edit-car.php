<?php
session_start();
require_once '../config/database.php';

function sanitize_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function upload_file($file, $target_dir = '../uploads/cars/') {
  if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
  }
  $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
  $new_filename = uniqid() . '.' . $file_extension;
  $target_file = $target_dir . $new_filename;
  $allowed_types = array('jpg', 'jpeg', 'png', 'gif', 'webp');
  if (!in_array($file_extension, $allowed_types)) {
    return false;
  }
  if ($file['size'] > 5 * 1024 * 1024) {
    return false;
  }
  if (move_uploaded_file($file['tmp_name'], $target_file)) {
    return $target_file;
  }
  return false;
}

function upload_multiple_files($files, $target_dir = '../uploads/cars/') {
  $paths = [];
  if (!isset($files['name']) || !is_array($files['name'])) return $paths;
  $count = count($files['name']);
  for ($i = 0; $i < $count; $i++) {
    if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
    $one = [
      'name' => $files['name'][$i],
      'type' => $files['type'][$i],
      'tmp_name' => $files['tmp_name'][$i],
      'error' => $files['error'][$i],
      'size' => $files['size'][$i],
    ];
    $p = upload_file($one, $target_dir);
    if ($p) $paths[] = $p;
  }
  return $paths;
}

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
  header('Location: cars.php');
  exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if($id <= 0){
  $msg = 'Invalid car ID';
  if(isset($_POST['ajax_request'])){ header('Content-Type: application/json'); echo json_encode(['success'=>false,'message'=>$msg]); exit; }
  $_SESSION['error_message'] = $msg; header('Location: cars.php'); exit;
}

try{
  // Fetch current row
  $st = $pdo->prepare('SELECT featured_image FROM tblcars WHERE id = :id');
  $st->execute([':id'=>$id]);
  $current = $st->fetch(PDO::FETCH_ASSOC);
  if(!$current){
    $msg = 'Car not found';
    if(isset($_POST['ajax_request'])){ header('Content-Type: application/json'); echo json_encode(['success'=>false,'message'=>$msg]); exit; }
    $_SESSION['error_message'] = $msg; header('Location: cars.php'); exit;
  }

  // Inputs
  $name = sanitize_input($_POST['name'] ?? '');
  $status = sanitize_input($_POST['status'] ?? 'Active');
  $description = sanitize_input($_POST['description'] ?? '');
  $car_type = sanitize_input($_POST['car_type'] ?? '');
  $brand = sanitize_input($_POST['brand'] ?? '');
  $model = sanitize_input($_POST['model'] ?? '');
  $category = sanitize_input($_POST['category'] ?? '');
  $plate_number = sanitize_input($_POST['plate_number'] ?? '');
  $vin_number = sanitize_input($_POST['vin_number'] ?? '');
  $main_location = sanitize_input($_POST['main_location'] ?? '');
  $fuel_type = sanitize_input($_POST['fuel_type'] ?? '');
  $odometer = sanitize_input($_POST['odometer'] ?? '');
  $color = sanitize_input($_POST['color'] ?? '');
  $year_of_car = intval($_POST['year_of_car'] ?? 0);
  $transmission = sanitize_input($_POST['transmission'] ?? '');
  $mileage = sanitize_input($_POST['mileage'] ?? '');
  $passengers = intval($_POST['passengers'] ?? 0);
  $seats = sanitize_input($_POST['seats'] ?? '');
  $doors = sanitize_input($_POST['doors'] ?? '');
  $air_bags = intval($_POST['air_bags'] ?? 0);

  $daily_price = floatval($_POST['daily_price'] ?? 0);
  $base_kilometers_per_day = intval($_POST['base_kilometers_per_day'] ?? 0);
  $kilometers_extra_price = floatval($_POST['kilometers_extra_price'] ?? 0);
  $unlimited_kilometers = isset($_POST['unlimited_kilometers']) ? 1 : 0;

  // features/extras
  $features_amenities = '[]';
  if (isset($_POST['features_amenities'])) {
    $faRaw = $_POST['features_amenities'];
    if (is_string($faRaw)) { $fa = json_decode($faRaw, true); if (!is_array($fa)) { $fa = []; } }
    else if (is_array($faRaw)) { $fa = $faRaw; } else { $fa = []; }
    $features_amenities = json_encode($fa);
  }
  $extra_services = '[]';
  if (isset($_POST['extra_services'])) {
    $esRaw = $_POST['extra_services'];
    if (is_string($esRaw)) { $es = json_decode($esRaw, true); if (!is_array($es)) { $es = []; } }
    else if (is_array($esRaw)) { $es = $esRaw; } else { $es = []; }
    $extra_services = json_encode($es);
  }

  // uploads
  $featured_image = $current['featured_image'];
  if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
    $uploaded = upload_file($_FILES['featured_image']);
    if ($uploaded) {
      // delete old one (best effort)
      if(!empty($featured_image)){
        if(strpos($featured_image, '../uploads/') === 0){ $abs = __DIR__ . '/' . $featured_image; if(is_file($abs)) @unlink($abs); }
        else if(strpos($featured_image, 'uploads/') === 0){ $abs = dirname(__DIR__) . '/' . $featured_image; if(is_file($abs)) @unlink($abs); }
      }
      $featured_image = $uploaded;
    }
  }
  $gallery_paths = [];
  if (isset($_FILES['gallery_images'])) {
    $gallery_paths = upload_multiple_files($_FILES['gallery_images']);
  }

  // Update
  $sql = "UPDATE tblcars SET
    name=:name, status=:status, description=:description,
    car_type=:car_type, brand=:brand, model=:model, category=:category,
    plate_number=:plate_number, vin_number=:vin_number, main_location=:main_location, fuel_type=:fuel_type,
    odometer=:odometer, color=:color, year_of_car=:year_of_car, transmission=:transmission, mileage=:mileage,
    passengers=:passengers, seats=:seats, doors=:doors, air_bags=:air_bags,
    daily_price=:daily_price, base_kilometers_per_day=:base_kilometers_per_day, kilometers_extra_price=:kilometers_extra_price,
    unlimited_kilometers=:unlimited_kilometers, features_amenities=:features_amenities, extra_services=:extra_services,
    updated_at=NOW(), featured_image=:featured_image
    WHERE id=:id";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':name'=>$name,
    ':status'=>$status,
    ':description'=>$description,
    ':car_type'=>$car_type,
    ':brand'=>$brand,
    ':model'=>$model,
    ':category'=>$category,
    ':plate_number'=>$plate_number,
    ':vin_number'=>$vin_number,
    ':main_location'=>$main_location,
    ':fuel_type'=>$fuel_type,
    ':odometer'=>$odometer,
    ':color'=>$color,
    ':year_of_car'=>$year_of_car,
    ':transmission'=>$transmission,
    ':mileage'=>$mileage,
    ':passengers'=>$passengers,
    ':seats'=>$seats,
    ':doors'=>$doors,
    ':air_bags'=>$air_bags,
    ':daily_price'=>$daily_price,
    ':base_kilometers_per_day'=>$base_kilometers_per_day,
    ':kilometers_extra_price'=>$kilometers_extra_price,
    ':unlimited_kilometers'=>$unlimited_kilometers,
    ':features_amenities'=>$features_amenities,
    ':extra_services'=>$extra_services,
    ':featured_image'=>$featured_image,
    ':id'=>$id,
  ]);

  // Insert added gallery images
  if (!empty($gallery_paths)) {
    $img_sql = "INSERT INTO car_images (car_id, image_path, is_featured) VALUES (:car_id, :image_path, 0)";
    $img_stmt = $pdo->prepare($img_sql);
    foreach ($gallery_paths as $img) {
      $img_stmt->execute([':car_id'=>$id, ':image_path'=>$img]);
    }
  }

  if(isset($_POST['ajax_request'])){
    header('Content-Type: application/json');
    echo json_encode(['success'=>true,'message'=>'Car updated']);
    exit;
  }
  $_SESSION['success_message'] = 'Car updated successfully';
  header('Location: car-details.php?id='.$id);
  exit;

} catch(Throwable $e){
  if(isset($_POST['ajax_request'])){
    header('Content-Type: application/json');
    echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
    exit;
  }
  $_SESSION['error_message'] = 'Failed to update car: '.$e->getMessage();
  header('Location: edit-car.php?id='.$id);
  exit;
}
