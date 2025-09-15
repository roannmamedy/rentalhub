<?php
session_start();
require_once '../config/database.php';
header_remove('X-Powered-By');

function json_out($ok, $msg){
  header('Content-Type: application/json');
  echo json_encode(['success'=>$ok,'message'=>$msg]);
  exit;
}

// Lightweight helper to add columns if needed
function ensure_column_exists(PDO $pdo, string $table, string $column, string $definition) {
  try {
    $check = $pdo->prepare("SHOW COLUMNS FROM `$table` LIKE :col");
    $check->execute([':col' => $column]);
    if ($check->rowCount() === 0) {
      $pdo->exec("ALTER TABLE `$table` ADD `$column` $definition");
    }
  } catch (Throwable $e) { /* ignore */ }
}

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
  $_SESSION['error_message'] = 'Invalid request';
  header('Location: cars.php');
  exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if($id <= 0){
  if(isset($_POST['ajax'])) json_out(false,'Invalid ID');
  $_SESSION['error_message'] = 'Invalid car ID';
  header('Location: cars.php');
  exit;
}

try{
  // Fetch file paths to delete from disk after DB delete
  $pdo->beginTransaction();

  $stmt = $pdo->prepare('SELECT featured_image FROM tblcars WHERE id = :id FOR UPDATE');
  $stmt->execute([':id'=>$id]);
  $car = $stmt->fetch(PDO::FETCH_ASSOC);
  if(!$car){
    $pdo->rollBack();
    if(isset($_POST['ajax'])) json_out(false,'Car not found');
    $_SESSION['error_message'] = 'Car not found';
    header('Location: cars.php');
    exit;
  }

  $imgStmt = $pdo->prepare('SELECT image_path FROM car_images WHERE car_id = :id');
  $imgStmt->execute([':id'=>$id]);
  $images = $imgStmt->fetchAll(PDO::FETCH_COLUMN) ?: [];

  try {
    // Try hard delete first
    $del = $pdo->prepare('DELETE FROM tblcars WHERE id = :id');
    $del->execute([':id'=>$id]);
    $pdo->commit();

    // Best-effort file cleanup after hard delete
    $paths = [];
    if(!empty($car['featured_image'])) $paths[] = $car['featured_image'];
    foreach($images as $p){ $paths[] = $p; }
    foreach($paths as $p){
      if(is_string($p) && strpos($p, '../uploads/') === 0){
        $abs = __DIR__ . '/' . $p;
        if(is_file($abs)) @unlink($abs);
      } else if(is_string($p) && strpos($p, 'uploads/') === 0){
        $abs = dirname(__DIR__) . '/' . $p;
        if(is_file($abs)) @unlink($abs);
      }
    }

    if(isset($_POST['ajax'])) json_out(true,'Car deleted');
    $_SESSION['success_message'] = 'Car deleted successfully';
    header('Location: cars.php');
    exit;
  } catch (PDOException $ex) {
    // FK constraint (e.g., bookings referencing this car) -> archive instead
    if($pdo->inTransaction()) $pdo->rollBack();

    // Ensure soft delete column exists
    ensure_column_exists($pdo, 'tblcars', 'deleted_at', 'DATETIME NULL DEFAULT NULL');

    // Also mark inactive/unavailable
    $arch = $pdo->prepare('UPDATE tblcars SET status = :status, is_available = 0, deleted_at = NOW(), updated_at = NOW() WHERE id = :id');
    $arch->execute([':status' => 'Inactive', ':id' => $id]);

    $msg = 'Car archived because it has related records (e.g., bookings).';
    if(isset($_POST['ajax'])) json_out(true, $msg);
    $_SESSION['success_message'] = $msg;
    header('Location: cars.php');
    exit;
  }
} catch(Throwable $e){
  if(isset($_POST['ajax'])) json_out(false,'Failed to delete: '.$e->getMessage());
  $_SESSION['error_message'] = 'Failed to delete car: '.$e->getMessage();
  header('Location: cars.php');
  exit;
}
