<?php
require __DIR__ . "/../../app/bootstrap/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/app/db/config.php";
require_once __DIR__ . '/../models/Order.php';

class PaymentController {
    private $config;

    public function __construct() {
        $this->config = require __DIR__ . '/../app_config/razorpay.php';
    }

    public function checkout() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /customer/login-form');
            exit;
        }

        if (empty($_SESSION['cart'])) {
            header('Location: /customer/cart');
            exit;
        }

        $totalAmountINR = 0;
        foreach ($_SESSION['cart'] as $item) {
            $totalAmountINR += (float)$item['price'] * (int)$item['quantity'];
        }
        
        $amountInPaisa = (int)round($totalAmountINR * 100);

        $view = __DIR__ . '/../views/payment/checkout.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }

    public function verify() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /');
            exit;
        }

        if (empty($_SESSION['cart']) || !isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }

        $mockPaymentId = $_POST['mock_payment_id'] ?? '';
        $paymentMethod = $_POST['payment_method'] ?? 'unknown';
        $shippingAddress = trim($_POST['shipping_address'] ?? 'Customer Address Not Supplied');

        // Extract and aggregate total amount from current operational session state
        $totalAmount = 0;
        foreach ($_SESSION['cart'] as $item) {
            $totalAmount += (float)$item['price'] * (int)$item['quantity'];
        }

        // Validate the incoming simulation token format
        if (strpos($mockPaymentId, 'pay_mock_') === 0) {
            
            // Rewrite your Order::saveOrder model database write to capture address metrics strings
            $db = Database::getConnection();
            $db->beginTransaction();
            
            // Relational SQL insert statement adapted dynamically to parse shipping address maps
            $stmt = $db->prepare("INSERT INTO orders (user_id, mock_payment_id, payment_method, total_amount, shipping_address) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $mockPaymentId, $paymentMethod, $totalAmount, $shippingAddress]);
            $orderId = $db->lastInsertId();

            $itemStmt = $db->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?)");
            foreach ($_SESSION['cart'] as $productId => $item) {
                $itemStmt->execute([$orderId, $productId, $item['name'], $item['price'], $item['quantity']]);
            }
            
            $db->commit();
            $_SESSION['cart'] = [];
            header('Location: /customer/checkout-success');
            exit;
        } else {
            header('Location: /customer/checkout-cancel');
            exit;
        }
    }

    public function success() {
        $view = __DIR__ . '/../views/payment/success.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }

    public function cancel() {
        $view = __DIR__ . '/../views/payment/cancel.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }
}
