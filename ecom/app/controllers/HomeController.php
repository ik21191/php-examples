<?php
use Monolog\Logger;
require __DIR__ . "/../../app/bootstrap/bootstrap.php";
require_once PROJECT_ROOT_PATH . "/app/db/config.php";
require_once __DIR__ . '/../models/Product.php';

class HomeController {
    private Logger $logger;

    public function __construct()
    {
        $this->logger = LoggerFactory::getLogger(__CLASS__);
    }

    public function index() {
        $this->logger->info("index() of HomeController is called.");
        $products = Product::getAll();
        $view = __DIR__ . '/../views/products/index.php';
        require_once __DIR__ . '/../views/layout/header.php';
    }
}
