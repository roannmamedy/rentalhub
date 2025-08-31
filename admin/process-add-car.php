<?php
session_start();
require_once '../config/database.php';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Basic Information
        $name = sanitize_input($_POST['name'] ?? '');
        $permalink = sanitize_input($_POST['permalink'] ?? '');

        // Handle featured image upload
        $featured_image = '';
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $uploaded_file = upload_file($_FILES['featured_image']);
            if ($uploaded_file) {
                $featured_image = $uploaded_file;
            }
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

        // Pricing Information
        $daily_price = floatval($_POST['daily_price'] ?? 0);
        $weekly_price = floatval($_POST['weekly_price'] ?? 0);
        $monthly_price = floatval($_POST['monthly_price'] ?? 0);
        $yearly_price = floatval($_POST['yearly_price'] ?? 0);
        $base_kilometers_per_day = intval($_POST['base_kilometers_per_day'] ?? 0);
        $kilometers_extra_price = floatval($_POST['kilometers_extra_price'] ?? 0);
        $unlimited_kilometers = isset($_POST['unlimited_kilometers']) ? 1 : 0;

        // Status (default values)
        $status = 'Active';
        $is_featured = 0;
        $is_available = 1;

        // SEO Information
        $meta_title = sanitize_input($_POST['meta_title'] ?? '');
        $meta_keywords = sanitize_input($_POST['meta_keywords'] ?? '');
        $meta_description = sanitize_input($_POST['meta_description'] ?? '');

        // Video Information (default values)
        $video_platform = '';
        $video_link = '';

        // Handle new form fields
        $features_amenities = json_encode($_POST['features_amenities'] ?? []);
        $extra_services = json_encode($_POST['extra_services'] ?? []);
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
            features_amenities, extra_services, created_at, updated_at
        ) VALUES (
            :name, :permalink, :featured_image, :car_type, :brand, :model, :category,
            :plate_number, :vin_number, :main_location, :fuel_type, :odometer, :color,
            :year_of_car, :transmission, :mileage, :passengers, :seats, :doors, :air_bags,
            :daily_price, :weekly_price, :monthly_price, :yearly_price, :base_kilometers_per_day,
            :kilometers_extra_price, :unlimited_kilometers, :status, :is_featured, :is_available,
            :meta_title, :meta_keywords, :meta_description, :video_platform, :video_link,
            :features_amenities, :extra_services, NOW(), NOW()
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
        $stmt->bindParam(':video_platform', $video_platform);
        $stmt->bindParam(':video_link', $video_link);
        $stmt->bindParam(':features_amenities', $features_amenities);
        $stmt->bindParam(':extra_services', $extra_services);

        // Execute the statement
        if ($stmt->execute()) {
            $car_id = $pdo->lastInsertId();
            
            // Insert damages if any
            if (!empty($_POST['damages'])) {
                $damages_data = json_decode($_POST['damages'], true);
                foreach ($damages_data as $damage) {
                    $damage_sql = "INSERT INTO car_damages (car_id, location, type, description, date_added) VALUES (:car_id, :location, :type, :description, :date_added)";
                    $damage_stmt = $pdo->prepare($damage_sql);
                    $damage_stmt->bindParam(':car_id', $car_id);
                    $damage_stmt->bindParam(':location', $damage['location']);
                    $damage_stmt->bindParam(':type', $damage['type']);
                    $damage_stmt->bindParam(':description', $damage['description']);
                    $damage_stmt->bindParam(':date_added', $damage['date']);
                    $damage_stmt->execute();
                }
            }
            
            // Insert FAQs if any
            if (!empty($_POST['faqs'])) {
                $faqs_data = json_decode($_POST['faqs'], true);
                foreach ($faqs_data as $faq) {
                    $faq_sql = "INSERT INTO car_faqs (car_id, question, answer) VALUES (:car_id, :question, :answer)";
                    $faq_stmt = $pdo->prepare($faq_sql);
                    $faq_stmt->bindParam(':car_id', $car_id);
                    $faq_stmt->bindParam(':question', $faq['question']);
                    $faq_stmt->bindParam(':answer', $faq['answer']);
                    $faq_stmt->execute();
                }
            }
            
            $_SESSION['success_message'] = "Car added successfully! Car ID: " . $car_id;
            
            // Return JSON response for AJAX
            if (isset($_POST['ajax_request'])) {
                echo json_encode(['success' => true, 'message' => 'Car added successfully!', 'car_id' => $car_id]);
                exit();
            } else {
                header("Location: cars.html");
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Error adding car to database.";
            
            // Return JSON response for AJAX
            if (isset($_POST['ajax_request'])) {
                echo json_encode(['success' => false, 'message' => 'Error adding car to database.']);
                exit();
            } else {
                header("Location: add-car.php");
                exit();
            }
        }

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Database error: " . $e->getMessage();
        header("Location: add-car.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        header("Location: add-car.php");
        exit();
    }
} else {
    // If not POST request, redirect to form
    header("Location: add-car.php");
    exit();
}
?>
