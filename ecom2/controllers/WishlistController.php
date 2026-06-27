<?php
// controllers/WishlistController.php
require_once __DIR__ . '/../models/Wishlist.php';

class WishlistController {
    
    private function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
    }

    public function index() {
        $this->checkAuth();
        $products = Wishlist::getByUserId($_SESSION['user_id']);
        
        $view = __DIR__ . '/../views/wishlist/index.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }

    public function toggle() {
        $this->checkAuth();
        $productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($productId > 0) {
            Wishlist::toggle($_SESSION['user_id'], $productId);
        }
        
        // Return user cleanly back to their referencing window frame layout link
        $referrer = $_SERVER['HTTP_REFERER'] ?? 'index.php';
        header("Location: " . $referrer);
        exit;
    }
}
