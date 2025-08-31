<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    try {
        if ($action === 'add_damage') {
            $location = trim($_POST['location'] ?? '');
            $type = trim($_POST['type'] ?? '');
            $description = trim($_POST['description'] ?? '');
            
            if (empty($location) || empty($type)) {
                echo json_encode(['success' => false, 'message' => 'Location and Type are required']);
                exit;
            }
            
            // For now, we'll store in session for demonstration
            // In a real application, you'd save to database
            if (!isset($_SESSION['damages'])) {
                $_SESSION['damages'] = [];
            }
            
            $damage = [
                'id' => uniqid(),
                'location' => $location,
                'type' => $type,
                'description' => $description,
                'date' => date('d M Y')
            ];
            
            $_SESSION['damages'][] = $damage;
            
            echo json_encode([
                'success' => true, 
                'message' => 'Damage added successfully',
                'damage' => $damage
            ]);
            
        } elseif ($action === 'add_faq') {
            $question = trim($_POST['question'] ?? '');
            $answer = trim($_POST['answer'] ?? '');
            
            if (empty($question) || empty($answer)) {
                echo json_encode(['success' => false, 'message' => 'Question and Answer are required']);
                exit;
            }
            
            // For now, we'll store in session for demonstration
            // In a real application, you'd save to database
            if (!isset($_SESSION['faqs'])) {
                $_SESSION['faqs'] = [];
            }
            
            $faq = [
                'id' => uniqid(),
                'question' => $question,
                'answer' => $answer
            ];
            
            $_SESSION['faqs'][] = $faq;
            
            echo json_encode([
                'success' => true, 
                'message' => 'FAQ added successfully',
                'faq' => $faq
            ]);
            
        } elseif ($action === 'delete_damage') {
            $damage_id = $_POST['damage_id'] ?? '';
            
            if (!empty($damage_id) && isset($_SESSION['damages'])) {
                $_SESSION['damages'] = array_filter($_SESSION['damages'], function($damage) use ($damage_id) {
                    return $damage['id'] !== $damage_id;
                });
            }
            
            echo json_encode(['success' => true, 'message' => 'Damage deleted successfully']);
            
        } elseif ($action === 'delete_faq') {
            $faq_id = $_POST['faq_id'] ?? '';
            
            if (!empty($faq_id) && isset($_SESSION['faqs'])) {
                $_SESSION['faqs'] = array_filter($_SESSION['faqs'], function($faq) use ($faq_id) {
                    return $faq['id'] !== $faq_id;
                });
            }
            
            echo json_encode(['success' => true, 'message' => 'FAQ deleted successfully']);
            
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
