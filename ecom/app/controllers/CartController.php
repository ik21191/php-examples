<?php
require __DIR__ . "/../../app/bootstrap/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/app/db/config.php";
require_once __DIR__ . '/../models/Product.php';

class CartController {
    public function index() {
        $cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $view = __DIR__ . '/../views/cart/index.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }

    public function addToCart() {
        $productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $product = Product::getById($productId);

        if ($product) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId]['quantity']++;
            } else {
                $_SESSION['cart'][$productId] = [
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'image' => $product['image'],
                    'quantity' => 1
                ];
            }
        }
        header('Location: /customer/cart');
        exit;
    }

    public function removeCartItem() {
        $productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }
        header('Location: /customer/cart');
        exit;
    }
}
