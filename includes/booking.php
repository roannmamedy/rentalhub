<?php
// Common booking helpers for multi-step flow
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

function h($v){return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');}
function imgp($p){ if(!$p) return ''; if(strpos($p, '../')===0) return substr($p, 3); return $p; }
function decode_arr($v){
  if ($v === null || $v === '') return [];
  if (is_array($v)) return $v;
  $d = json_decode($v, true);
  if (is_array($d)) return $d;
  $d2 = json_decode((string)$v, true);
  return is_array($d2) ? $d2 : [];
}

function redirect_to($url){
  header('Location: ' . $url);
  exit;
}

function booking_session(){
  if (!isset($_SESSION['booking'])) {
    $_SESSION['booking'] = [
      'car' => null,
      'itinerary' => null,
      'addons' => [ 'extras' => [], 'insurance' => null ],
      'driver' => null,
      'payment' => null,
      'totals' => [ 'days' => 0, 'base' => 0.0, 'addons' => 0.0, 'total' => 0.0 ],
      'order' => null,
    ];
  }
  return $_SESSION['booking'];
}

function set_booking_session($data){
  $_SESSION['booking'] = $data;
}

function ensure_car_in_booking($carId){
  global $pdo;
  if (!$carId && empty($_SESSION['booking']['car'])) return false;
  if ($carId) {
    $stmt = $pdo->prepare("SELECT * FROM tblcars WHERE id = :id");
    $stmt->execute([':id' => (int)$carId]);
    $car = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$car) return false;
    $car['featured_image'] = imgp($car['featured_image'] ?? '') ?: 'assets/img/cars/car-01.jpg';
    $b = booking_session();
    $b['car'] = $car;
    set_booking_session($b);
    return true;
  }
  return isset($_SESSION['booking']['car']);
}

function get_car_images($carId){
  global $pdo;
  $gstmt = $pdo->prepare("SELECT image_path FROM car_images WHERE car_id = :id ORDER BY is_featured DESC, id ASC");
  $gstmt->execute([':id' => (int)$carId]);
  $imgs = $gstmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
  return array_map('imgp', $imgs);
}

function calculate_days($pickupDate, $pickupTime, $dropDate, $dropTime){
  $start = strtotime(trim($pickupDate) . ' ' . ($pickupTime ?: '10:00'));
  $end   = strtotime(trim($dropDate) . ' ' . ($dropTime ?: '10:00'));
  if (!$start || !$end || $end <= $start) return 1;
  $days = ceil(($end - $start) / 86400);
  return max(1, (int)$days);
}

function get_extra_price_map(){
  // Per rental pricing for extra_services keys
  return [
    'navigation' => 10.00,
    'wifi_hotspot' => 8.00,
    'child_safety_seats' => 7.50,
    'roadside_assistance' => 20.00,
    'usb_charger' => 3.00,
    'toll_pass' => 12.00,
  'satellite_radio' => 9.00,
    'dash_cam' => 6.00,
    'fuel_pre_purchase' => 30.00,
    'express_checkin_checkout' => 5.00,
  ];
}

function calculate_totals(&$booking){
  // Days & car context
  $days = (int)($booking['totals']['days'] ?? 0);
  $days = max(1, $days);
  $car = $booking['car'] ?? null;
  $it  = $booking['itinerary'] ?? [];
  $bt = strtolower($it['booking_type'] ?? 'daily');

  // Compute base according to booking type and days
  $daily   = $car ? (float)($car['daily_price']   ?? 0.0) : 0.0;
  $weekly  = $car ? (float)($car['weekly_price']  ?? 0.0) : 0.0;
  $monthly = $car ? (float)($car['monthly_price'] ?? 0.0) : 0.0;
  $yearly  = $car ? (float)($car['yearly_price']  ?? 0.0) : 0.0;

  $base = 0.0;
  switch ($bt) {
    case 'weekly':
      if ($weekly > 0) {
        $base = ceil($days / 7) * $weekly;
      } else {
        $base = $daily * $days;
      }
      break;
    case 'monthly':
      if ($monthly > 0) {
        $base = ceil($days / 30) * $monthly;
      } else {
        $base = $daily * $days;
      }
      break;
    case 'yearly':
      if ($yearly > 0) {
        $base = ceil($days / 365) * $yearly;
      } else {
        $base = $daily * $days;
      }
      break;
    case 'daily':
    default:
      $base = $daily * $days;
  }
  // Addons
  $addonsTotal = 0.0;
  $map = get_extra_price_map();
  $selected = $booking['addons']['extras'] ?? [];
  foreach ($selected as $key) {
    $addonsTotal += isset($map[$key]) ? (float)$map[$key] : 5.00;
  }
  // Insurance
  if (!empty($booking['addons']['insurance'])) {
    $addonsTotal += (float)$booking['addons']['insurance']['price'];
  }
  // Acting driver fee (flat per booking in this simple model)
  if (!empty($booking['addons']['driver']) && (($booking['addons']['driver']['type'] ?? '') === 'acting')) {
    $addonsTotal += (float)($booking['addons']['driver']['price'] ?? 0.0);
  }
  $booking['totals']['base'] = round($base, 2);
  $booking['totals']['addons'] = round($addonsTotal, 2);
  $booking['totals']['total'] = round($base + $addonsTotal, 2);
}

function fetch_car_insurance($carId){
  global $pdo;
  $stmt = $pdo->prepare("SELECT id, insurance_name, price, benefits FROM car_insurance WHERE car_id = :id ORDER BY id ASC");
  $stmt->execute([':id' => (int)$carId]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

function ensure_booking_tables(PDO $pdo){
  $pdo->exec("CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(32) NOT NULL,
    car_id INT NOT NULL,
    pickup_location VARCHAR(255) DEFAULT NULL,
    dropoff_location VARCHAR(255) DEFAULT NULL,
    pickup_datetime DATETIME NOT NULL,
    dropoff_datetime DATETIME NOT NULL,
    total_days INT NOT NULL,
    base_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    addons_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    insurance_name VARCHAR(100) DEFAULT NULL,
    insurance_price DECIMAL(10,2) DEFAULT NULL,
    addons_json JSON DEFAULT NULL,
    status ENUM('pending','paid','cancelled') NOT NULL DEFAULT 'pending',
    customer_name VARCHAR(200) DEFAULT NULL,
    customer_email VARCHAR(200) DEFAULT NULL,
    customer_phone VARCHAR(50) DEFAULT NULL,
    customer_license VARCHAR(100) DEFAULT NULL,
  driver_document_path VARCHAR(512) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_bookings_car FOREIGN KEY (car_id) REFERENCES tblcars(id) ON DELETE RESTRICT
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

  $pdo->exec("CREATE TABLE IF NOT EXISTS booking_payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    method VARCHAR(50) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending','captured','failed') NOT NULL DEFAULT 'pending',
    transaction_ref VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_payment_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
}

function create_booking(PDO $pdo, $booking){
  ensure_booking_tables($pdo);
  $orderNo = strtoupper(dechex(time())) . '-' . substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 4);
  $it = $booking['itinerary'];
  $driver = $booking['driver'];
  $addons = $booking['addons'];
  $tot = $booking['totals'];
  $pickupDt = date('Y-m-d H:i:s', strtotime($it['pickup_date'].' '.($it['pickup_time'] ?? '10:00')));
  $dropDt   = date('Y-m-d H:i:s', strtotime($it['dropoff_date'].' '.($it['dropoff_time'] ?? '10:00')));

  $insName = $addons['insurance']['name'] ?? null;
  $insPrice = $addons['insurance']['price'] ?? null;
  $addonsJson = json_encode([
    'extras' => $addons['extras'] ?? [],
    'insurance' => $addons['insurance'] ?? null,
  ]);

  $docPath = $booking['driver']['document_path'] ?? null;
  try {
    // Preferred insert including document path
    $stmt = $pdo->prepare("INSERT INTO bookings
      (order_number, car_id, pickup_location, dropoff_location, pickup_datetime, dropoff_datetime, total_days,
       base_amount, addons_amount, total_amount, insurance_name, insurance_price, addons_json, status, customer_name, customer_email, customer_phone, customer_license, driver_document_path)
       VALUES
      (:order_number, :car_id, :pickup_location, :dropoff_location, :pickup_dt, :drop_dt, :days,
       :base_amount, :addons_amount, :total_amount, :insurance_name, :insurance_price, :addons_json, :status, :cust_name, :cust_email, :cust_phone, :cust_license, :doc_path)");

    $stmt->execute([
      ':order_number' => $orderNo,
      ':car_id' => (int)$booking['car']['id'],
      ':pickup_location' => $it['pickup_location'] ?? null,
      ':dropoff_location' => $it['dropoff_location'] ?? null,
      ':pickup_dt' => $pickupDt,
      ':drop_dt' => $dropDt,
      ':days' => (int)$tot['days'],
      ':base_amount' => (float)$tot['base'],
      ':addons_amount' => (float)$tot['addons'],
      ':total_amount' => (float)$tot['total'],
      ':insurance_name' => $insName,
      ':insurance_price' => $insPrice,
      ':addons_json' => $addonsJson,
      ':status' => 'pending',
      ':cust_name' => $driver['name'] ?? null,
      ':cust_email' => $driver['email'] ?? null,
      ':cust_phone' => $driver['phone'] ?? null,
      ':cust_license' => $driver['license'] ?? null,
      ':doc_path' => $docPath,
    ]);
  } catch (Throwable $e) {
    // Fallback for schema without driver_document_path
    $stmt = $pdo->prepare("INSERT INTO bookings
      (order_number, car_id, pickup_location, dropoff_location, pickup_datetime, dropoff_datetime, total_days,
       base_amount, addons_amount, total_amount, insurance_name, insurance_price, addons_json, status, customer_name, customer_email, customer_phone, customer_license)
       VALUES
      (:order_number, :car_id, :pickup_location, :dropoff_location, :pickup_dt, :drop_dt, :days,
       :base_amount, :addons_amount, :total_amount, :insurance_name, :insurance_price, :addons_json, :status, :cust_name, :cust_email, :cust_phone, :cust_license)");

    $stmt->execute([
      ':order_number' => $orderNo,
      ':car_id' => (int)$booking['car']['id'],
      ':pickup_location' => $it['pickup_location'] ?? null,
      ':dropoff_location' => $it['dropoff_location'] ?? null,
      ':pickup_dt' => $pickupDt,
      ':drop_dt' => $dropDt,
      ':days' => (int)$tot['days'],
      ':base_amount' => (float)$tot['base'],
      ':addons_amount' => (float)$tot['addons'],
      ':total_amount' => (float)$tot['total'],
      ':insurance_name' => $insName,
      ':insurance_price' => $insPrice,
      ':addons_json' => $addonsJson,
      ':status' => 'pending',
      ':cust_name' => $driver['name'] ?? null,
      ':cust_email' => $driver['email'] ?? null,
      ':cust_phone' => $driver['phone'] ?? null,
      ':cust_license' => $driver['license'] ?? null,
    ]);
  }

  return [ 'id' => (int)$pdo->lastInsertId(), 'order_number' => $orderNo ];
}

/**
 * Ensure customers table exists (for accounts created during booking)
 */
function ensure_customers_table(PDO $pdo){
  $pdo->exec("CREATE TABLE IF NOT EXISTS customers (
    id INT(11) NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    country VARCHAR(100) DEFAULT NULL,
    city VARCHAR(100) DEFAULT NULL,
    pincode VARCHAR(20) DEFAULT NULL,
    company VARCHAR(255) DEFAULT NULL,
    address VARCHAR(255) DEFAULT NULL,
    notes TEXT DEFAULT NULL,
    driver_type ENUM('self','acting') DEFAULT 'self',
    license VARCHAR(100) DEFAULT NULL,
    document_path VARCHAR(500) DEFAULT NULL,
    document_mime VARCHAR(100) DEFAULT NULL,
    password_hash VARCHAR(255) DEFAULT NULL,
    status ENUM('pending','active','disabled') NOT NULL DEFAULT 'pending',
    email_verified_at TIMESTAMP NULL DEFAULT NULL,
    terms_accepted TINYINT(1) NOT NULL DEFAULT 0,
    terms_accepted_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uk_customers_email (email),
    KEY idx_customers_phone (phone),
    KEY idx_customers_status (status),
    KEY idx_customers_created_at (created_at)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

/**
 * Create or update a customer by email. Returns the customer id.
 */
function upsert_customer(PDO $pdo, array $data){
  ensure_customers_table($pdo);
  // Normalize
  $data = array_merge([
    'first_name' => null,
    'last_name' => null,
    'email' => null,
    'phone' => null,
    'country' => null,
    'city' => null,
    'pincode' => null,
    'company' => null,
    'address' => null,
    'notes' => null,
    'driver_type' => 'self',
    'license' => null,
    'document_path' => null,
    'document_mime' => null,
    'terms_accepted' => 0,
  ], $data);

  $sql = "INSERT INTO customers
    (first_name, last_name, email, phone, country, city, pincode, company, address, notes, driver_type, license, document_path, document_mime, status, terms_accepted, terms_accepted_at)
    VALUES
    (:first_name, :last_name, :email, :phone, :country, :city, :pincode, :company, :address, :notes, :driver_type, :license, :document_path, :document_mime, 'pending', :terms_accepted, CASE WHEN :terms_accepted = 1 THEN CURRENT_TIMESTAMP ELSE NULL END)
    ON DUPLICATE KEY UPDATE
      first_name = VALUES(first_name),
      last_name = VALUES(last_name),
      phone = VALUES(phone),
      country = VALUES(country),
      city = VALUES(city),
      pincode = VALUES(pincode),
      company = VALUES(company),
      address = VALUES(address),
      notes = VALUES(notes),
      driver_type = VALUES(driver_type),
      license = VALUES(license),
      document_path = COALESCE(VALUES(document_path), document_path),
      document_mime = COALESCE(VALUES(document_mime), document_mime),
      terms_accepted = GREATEST(terms_accepted, VALUES(terms_accepted)),
      terms_accepted_at = COALESCE(terms_accepted_at, VALUES(terms_accepted_at)),
      updated_at = CURRENT_TIMESTAMP";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':first_name' => (string)$data['first_name'],
    ':last_name' => (string)$data['last_name'],
    ':email' => (string)$data['email'],
    ':phone' => (string)$data['phone'],
    ':country' => $data['country'],
    ':city' => $data['city'],
    ':pincode' => $data['pincode'],
    ':company' => $data['company'],
    ':address' => $data['address'],
    ':notes' => $data['notes'],
    ':driver_type' => $data['driver_type'] ?: 'self',
    ':license' => $data['license'],
    ':document_path' => $data['document_path'],
    ':document_mime' => $data['document_mime'],
    ':terms_accepted' => !empty($data['terms_accepted']) ? 1 : 0,
  ]);

  // Get id reliably
  $sel = $pdo->prepare("SELECT id FROM customers WHERE email = :email LIMIT 1");
  $sel->execute([':email' => (string)$data['email']]);
  $row = $sel->fetch(PDO::FETCH_ASSOC);
  return $row ? (int)$row['id'] : 0;
}

?>