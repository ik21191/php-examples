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
        require_once __DIR__ . '/../models/Review.php'; // Pull in model dependencies hook
        
        $productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $product = Product::getById($productId);

        if (!$product) {
            header('Location: index.php');
            exit;
        }

        // Query historical loop review datasets metrics arrays state variables 
        $reviews = Review::getByProductId($productId);
        $ratingStats = Review::getAverageRating($productId);

        $view = __DIR__ . '/../views/products/details.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }
}
