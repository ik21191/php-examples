<?php
require __DIR__ . "/../../app/bootstrap/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/app/db/config.php";
require_once __DIR__ . '/../models/Product.php';

class HomeController {
    public function index() {
        $products = Product::getAll();
        $view = __DIR__ . '/../views/products/index.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }
}
