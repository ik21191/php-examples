<?php
// controllers/ProfileController.php
require_once __DIR__ . '/../models/Order.php';

class ProfileController {
    public function orders() {
        // Enforce user authentication checkpoint check
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        // Fetch current contextual records matching active session index
        $userOrders = Order::getOrdersByUserId($_SESSION['user_id']);

        // Pass control straight forward over onto our responsive profile dashboard view layer
        $view = __DIR__ . '/../views/profile/orders.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }
}
