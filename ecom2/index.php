<?php
// index.php

session_start();

$controllerParam = isset($_GET['controller']) ? $_GET['controller'] : 'product';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($controllerParam) {
    case 'auth':
        $controllerName = 'AuthController';
        break;
    case 'cart':
        $controllerName = 'CartController';
        break;
    case 'payment':
        $controllerName = 'PaymentController';
        break;
    case 'wishlist':
        $controllerName = 'WishlistController';
        break;
    case 'profile':
        $controllerName = 'ProfileController';
        break;
    case 'review':
        $controllerName = 'ReviewController';
        break;
    case 'invoice':
        $controllerName = 'InvoiceController';
        break;
    case 'admin':
        $controllerName = 'AdminController';
        break;
    case 'product':
    default:
        $controllerName = 'ProductController';
        break;
}

$controllerPath = __DIR__ . "/controllers/" . $controllerName . ".php";

if (file_exists($controllerPath)) {
    require_once $controllerPath;
    $controllerInstance = new $controllerName();
    
    if (method_exists($controllerInstance, $action)) {
        $controllerInstance->$action();
    } else {
        echo "404 - Action Not Found";
    }
} else {
    echo "404 - Controller Not Found";
}
