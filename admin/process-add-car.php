<?php
session_start();
require_once '../config/database.php';

// Ensure schema compatibility (lightweight auto-migration)
function ensure_column_exists(PDO $pdo, string $table, string $column, string $definition) {
    try {
        $check = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE :col");
        $check->execute([':col' => $column]);
        if ($check->rowCount() === 0) {
            $pdo->exec("ALTER TABLE `$table` ADD `$column` $definition");
        }
    } catch (Throwable $e) {
        // Silent fail to avoid breaking request; logs could be added in real app
    }
}

// Function to sanitize input data
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to handle file upload
function upload_file($file, $target_dir = '../uploads/cars/') {
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;

    // Check file type
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif', 'webp');
    if (!in_array($file_extension, $allowed_types)) {
        return false;
    }

    // Check file size (5MB max)
    if ($file['size'] > 5 * 1024 * 1024) {
        return false;
    }

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return $target_file;
    }

    return false;
}

// Handle multiple uploads for gallery
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
    // Make sure new fields exist
    ensure_column_exists($pdo, 'tblcars', 'description', 'TEXT NULL');
        // Ensure car_images table exists for gallery uploads
        try {
            $pdo->exec("CREATE TABLE IF NOT EXISTS car_images (
                id INT AUTO_INCREMENT PRIMARY KEY,
                car_id INT NOT NULL,
                image_path VARCHAR(255) NOT NULL,
                is_featured TINYINT(1) NOT NULL DEFAULT 0,
                created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (car_id) REFERENCES tblcars(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        } catch (Throwable $e) { /* ignore */ }
        // Basic Information
        $name = sanitize_input($_POST['name'] ?? '');
    // Permalink is currently commented out in form
    $permalink = sanitize_input($_POST['permalink'] ?? '');

        // Handle featured image upload
        $featured_image = '';
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $uploaded_file = upload_file($_FILES['featured_image']);
            if ($uploaded_file) {
                $featured_image = $uploaded_file;
            }
        }

        // Handle gallery images upload
        $gallery_paths = [];
        if (isset($_FILES['gallery_images'])) {
            $gallery_paths = upload_multiple_files($_FILES['gallery_images']);
        }

        // Car Details
        $car_type = sanitize_input($_POST['car_type'] ?? '');
        $brand = sanitize_input($_POST['brand'] ?? '');
        $model = sanitize_input($_POST['model'] ?? '');
        $category = sanitize_input($_POST['category'] ?? 'Car');
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
    // New optional description field
    $description = sanitize_input($_POST['description'] ?? '');

        // Pricing Information
        $daily_price = floatval($_POST['daily_price'] ?? 0);
    // Only daily price is required; others default to 0
    $weekly_price = 0;
    $monthly_price = 0;
    $yearly_price = 0;
        $base_kilometers_per_day = intval($_POST['base_kilometers_per_day'] ?? 0);
        $kilometers_extra_price = floatval($_POST['kilometers_extra_price'] ?? 0);
        $unlimited_kilometers = isset($_POST['unlimited_kilometers']) ? 1 : 0;

    // Status from form (default Active)
    $status = sanitize_input($_POST['status'] ?? 'Active');
        $is_featured = 0;
        $is_available = 1;

        // SEO Information
    // SEO currently disabled in UI; keep empty
    $meta_title = '';
    $meta_keywords = '';
    $meta_description = '';

        // Video Information (default values)
    // Video currently disabled in UI; use NULLs so ENUM/VARCHAR columns don't warn
    $video_platform = null;
    $video_link = null;

    // Insurance selection (dropdown)
    $insurance_option = sanitize_input($_POST['insurance_option'] ?? 'none');

        // Handle new form fields (ensure encode-once behavior)
        $features_amenities = '[]';
        if (isset($_POST['features_amenities'])) {
            $faRaw = $_POST['features_amenities'];
            if (is_string($faRaw)) {
                $fa = json_decode($faRaw, true);
                if (!is_array($fa)) { $fa = []; }
            } else if (is_array($faRaw)) {
                $fa = $faRaw;
            } else { $fa = []; }
            $features_amenities = json_encode($fa);
        }

        $extra_services = '[]';
        if (isset($_POST['extra_services'])) {
            $esRaw = $_POST['extra_services'];
            if (is_string($esRaw)) {
                $es = json_decode($esRaw, true);
                if (!is_array($es)) { $es = []; }
            } else if (is_array($esRaw)) {
                $es = $esRaw;
            } else { $es = []; }
            $extra_services = json_encode($es);
        }
        $pricing_types = json_encode($_POST['pricing_types'] ?? []);
        $damages = json_encode($_POST['damages'] ?? []);
        $faqs = json_encode($_POST['faqs'] ?? []);

        // Prepare SQL statement
        $sql = "INSERT INTO tblcars (
            name, permalink, featured_image, car_type, brand, model, category,
            plate_number, vin_number, main_location, fuel_type, odometer, color,
            year_of_car, transmission, mileage, passengers, seats, doors, air_bags,
            daily_price, weekly_price, monthly_price, yearly_price, base_kilometers_per_day,
            kilometers_extra_price, unlimited_kilometers, status, is_featured, is_available,
            meta_title, meta_keywords, meta_description, video_platform, video_link,
            features_amenities, extra_services, description, created_at, updated_at
        ) VALUES (
            :name, :permalink, :featured_image, :car_type, :brand, :model, :category,
            :plate_number, :vin_number, :main_location, :fuel_type, :odometer, :color,
            :year_of_car, :transmission, :mileage, :passengers, :seats, :doors, :air_bags,
            :daily_price, :weekly_price, :monthly_price, :yearly_price, :base_kilometers_per_day,
            :kilometers_extra_price, :unlimited_kilometers, :status, :is_featured, :is_available,
            :meta_title, :meta_keywords, :meta_description, :video_platform, :video_link,
            :features_amenities, :extra_services, :description, NOW(), NOW()
        )";

        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':permalink', $permalink);
        $stmt->bindParam(':featured_image', $featured_image);
        $stmt->bindParam(':car_type', $car_type);
        $stmt->bindParam(':brand', $brand);
        $stmt->bindParam(':model', $model);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':plate_number', $plate_number);
        $stmt->bindParam(':vin_number', $vin_number);
        $stmt->bindParam(':main_location', $main_location);
        $stmt->bindParam(':fuel_type', $fuel_type);
        $stmt->bindParam(':odometer', $odometer);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':year_of_car', $year_of_car);
        $stmt->bindParam(':transmission', $transmission);
        $stmt->bindParam(':mileage', $mileage);
        $stmt->bindParam(':passengers', $passengers);
        $stmt->bindParam(':seats', $seats);
        $stmt->bindParam(':doors', $doors);
        $stmt->bindParam(':air_bags', $air_bags);
        $stmt->bindParam(':daily_price', $daily_price);
        $stmt->bindParam(':weekly_price', $weekly_price);
        $stmt->bindParam(':monthly_price', $monthly_price);
        $stmt->bindParam(':yearly_price', $yearly_price);
        $stmt->bindParam(':base_kilometers_per_day', $base_kilometers_per_day);
        $stmt->bindParam(':kilometers_extra_price', $kilometers_extra_price);
        $stmt->bindParam(':unlimited_kilometers', $unlimited_kilometers);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':is_featured', $is_featured);
        $stmt->bindParam(':is_available', $is_available);
        $stmt->bindParam(':meta_title', $meta_title);
        $stmt->bindParam(':meta_keywords', $meta_keywords);
        $stmt->bindParam(':meta_description', $meta_description);
        // Bind video fields as NULL when not provided
        if ($video_platform === null) {
            $stmt->bindValue(':video_platform', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':video_platform', $video_platform, PDO::PARAM_STR);
        }
        if ($video_link === null) {
            $stmt->bindValue(':video_link', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':video_link', $video_link, PDO::PARAM_STR);
        }
        $stmt->bindParam(':features_amenities', $features_amenities);
        $stmt->bindParam(':extra_services', $extra_services);
    $stmt->bindParam(':description', $description);

        // Execute the statement
        if ($stmt->execute()) {
            $car_id = $pdo->lastInsertId();

            // Insert gallery images if any
            if (!empty($gallery_paths)) {
                $img_sql = "INSERT INTO car_images (car_id, image_path, is_featured) VALUES (:car_id, :image_path, 0)";
                $img_stmt = $pdo->prepare($img_sql);
                foreach ($gallery_paths as $img) {
                    $img_stmt->bindParam(':car_id', $car_id);
                    $img_stmt->bindParam(':image_path', $img);
                    $img_stmt->execute();
                }
            }

            // Insert insurance row if selected
            if (!empty($insurance_option) && $insurance_option !== 'none') {
                $ins_price = 0.00;
                switch ($insurance_option) {
                    case 'Full Premium Insurance':
                        $ins_price = 200.00; break;
                    case 'Roadside Assistance':
                        $ins_price = 250.00; break;
                    case 'Liability Insurance':
                        $ins_price = 150.00; break;
                    case 'Personal Accident Insurance':
                        $ins_price = 300.00; break;
                }
                $ins_sql = "INSERT INTO car_insurance (car_id, insurance_name, price, benefits) VALUES (:car_id, :insurance_name, :price, :benefits)";
                $ins_stmt = $pdo->prepare($ins_sql);
                $benefits = null; // could be extended later
                $ins_stmt->bindParam(':car_id', $car_id);
                $ins_stmt->bindParam(':insurance_name', $insurance_option);
                $ins_stmt->bindParam(':price', $ins_price);
                $ins_stmt->bindParam(':benefits', $benefits);
                $ins_stmt->execute();
            }
            
            // Insert damages if any (from session)
            $damages_data = isset($_SESSION['damages']) ? $_SESSION['damages'] : [];
            
            if (!empty($damages_data) && is_array($damages_data)) {
                foreach ($damages_data as $damage) {
                    $damage_sql = "INSERT INTO car_damages (car_id, location, type, description, date_added) VALUES (:car_id, :location, :type, :description, :date_added)";
                    $damage_stmt = $pdo->prepare($damage_sql);
                    $damage_stmt->bindParam(':car_id', $car_id);
                    $damage_stmt->bindParam(':location', $damage['location']);
                    $damage_stmt->bindParam(':type', $damage['type']);
                    $damage_stmt->bindParam(':description', $damage['description']);
                    $date_added = date('Y-m-d');
                    $damage_stmt->bindParam(':date_added', $date_added);
                    $damage_stmt->execute();
                }
            }
            
            // FAQ disabled; ignore
            
            $_SESSION['success_message'] = "Car added successfully! Car ID: " . $car_id;
            // Clear session-stored temp data
            unset($_SESSION['damages']);
            unset($_SESSION['faqs']);
            
            // Return JSON response for AJAX
            if (isset($_POST['ajax_request'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Car added successfully!', 'car_id' => $car_id]);
                exit();
            } else {
                header("Location: cars.php");
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Error adding car to database.";
            
            // Return JSON response for AJAX
            if (isset($_POST['ajax_request'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Error adding car to database.']);
                exit();
            } else {
                header("Location: add-car.php");
                exit();
            }
        }

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
        if (isset($_POST['ajax_request'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $_SESSION['error_message']]);
            exit();
        } else {
            header("Location: add-car.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        if (isset($_POST['ajax_request'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $_SESSION['error_message']]);
            exit();
        } else {
            header("Location: add-car.php");
            exit();
        }
    }
} else {
    // If not POST request, redirect to form
    header("Location: add-car.php");
    exit();
}
?>
