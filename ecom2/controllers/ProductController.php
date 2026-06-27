<?php
// controllers/ProductController.php
require_once __DIR__ . '/../models/Product.php';

class ProductController {
    public function index() {
        $products = Product::getAll();
        $view = __DIR__ . '/../views/products/index.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }

    public function details() {
        $productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $product = Product::getById($productId);

        // If the item doesn't exist or parameter array is empty, redirect safely to home catalog
        if (!$product) {
            header('Location: index.php');
            exit;
        }

        // Pass control control variables directly over to details layout engine
        $view = __DIR__ . '/../views/products/details.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }
}
