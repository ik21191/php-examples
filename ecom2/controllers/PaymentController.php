<?php
// controllers/PaymentController.php
require_once __DIR__ . '/../models/Order.php';

class PaymentController {
    private $config;

    public function __construct() {
        $this->config = require __DIR__ . '/../config/razorpay.php';
    }

    public function checkout() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if (empty($_SESSION['cart'])) {
            header('Location: index.php?controller=cart&action=index');
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
            header('Location: index.php');
            exit;
        }

        if (empty($_SESSION['cart']) || !isset($_SESSION['user_id'])) {
            header('Location: index.php');
            exit;
        }

        $mockPaymentId = $_POST['mock_payment_id'] ?? '';
        $paymentMethod = $_POST['payment_method'] ?? 'unknown';

        // Extract and aggregate total amount from current operational session state
        $totalAmount = 0;
        foreach ($_SESSION['cart'] as $item) {
            $totalAmount += (float)$item['price'] * (int)$item['quantity'];
        }

        // Validate the incoming simulation token format
        if (strpos($mockPaymentId, 'pay_mock_') === 0) {
            
            // Dispatch parameters out straight into our transactional Database storage model
            $orderSaved = Order::saveOrder(
                $_SESSION['user_id'],
                $mockPaymentId,
                $paymentMethod,
                $totalAmount,
                $_SESSION['cart']
            );

            if ($orderSaved) {
                $_SESSION['cart'] = []; // Clear active shopping cart state upon successful database write
                header('Location: index.php?controller=payment&action=success');
                exit;
            } else {
                die("Error: Could not permanently register order logging variables into MySQL database.");
            }
        } else {
            header('Location: index.php?controller=payment&action=cancel');
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
