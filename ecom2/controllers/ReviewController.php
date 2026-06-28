<?php
// controllers/ReviewController.php
require_once __DIR__ . '/../models/Review.php';

class ReviewController {
    public function add() {
        session_start();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }

        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $rating    = isset($_POST['rating']) ? (int)$_POST['rating'] : 5;
        $comment   = trim($_POST['comment'] ?? '');

        if ($productId > 0 && $rating >= 1 && $rating <= 5 && !empty($comment)) {
            Review::create($productId, $_SESSION['user_id'], $rating, $comment);
        }

        // Return user to the original product overview timeline panel page location
        header("Location: index.php?controller=product&action=details&id=" . $productId);
        exit;
    }
}
