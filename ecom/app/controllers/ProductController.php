<?php
require __DIR__ . "/../../app/bootstrap/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/app/db/config.php";
require_once __DIR__ . '/../models/Product.php';

class ProductController {
    public function productDetails() {
        require_once __DIR__ . '/../models/Review.php'; // Pull in model dependencies hook
        
        $productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $product = Product::getById($productId);

        if (!$product) {
            header('Location: /');
            exit;
        }

        // Query historical loop review datasets metrics arrays state variables 
        $reviews = Review::getByProductId($productId);
        $ratingStats = Review::getAverageRating($productId);

        $view = __DIR__ . '/../views/products/details.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }
}
