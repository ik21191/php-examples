<?php

session_start();

require_once __DIR__ . '/app/core/Router.php';
require_once __DIR__ . '/app/helpers/LoggerFactory.php';
//require_once __DIR__ . '/app/helpers/EnvLoader.php';


//$logger = LoggerFactory::getLogger(__FILE__);

//$envLoader = EnvLoader::getInstance();

//$logger->info("Processing request for URI: " . $_SERVER['REQUEST_URI']);

$router = new Router();

$router->get('/', 'HomeController@index');

//customer login, register and logout
$router->get('/customer/login-form', 'AuthController@loginForm');
$router->post('/customer/authenticate', 'AuthController@authenticate');
$router->get('/customer/register-form', 'AuthController@registerForm');
$router->post('/customer/register-user', 'AuthController@register');
$router->get('/customer/logout', 'AuthController@logout');
//customer forgot password
$router->get('/customer/forgot-password-form', 'AuthController@forgotPasswordForm');
$router->post('/customer/generate-reset-password-link', 'AuthController@generateResetPasswordLink');
$router->get('/customer/verify-reset-password-link', 'AuthController@verifyResetPasswordLink');
$router->post('/customer/reset-password', 'AuthController@resetPassword');


$router->get('/product/product-details', 'ProductController@productDetails');

//customer cart
$router->get('/customer/cart', 'CartController@index');
$router->get('/customer/add-to-cart', 'CartController@addToCart');
$router->get('/customer/remove-cart-item', 'CartController@removeCartItem');

//customer checkout
$router->post('/customer/checkout', 'PaymentController@checkout');
$router->post('/customer/checkout-verify', 'PaymentController@verify');
$router->get('/customer/checkout-success', 'PaymentController@success');
$router->get('/customer/checkout-cancel', 'PaymentController@cancel');

//customer wishlist
$router->get('/customer/wishlist', 'WishlistController@index');
$router->get('/customer/wishlist-toggle', 'WishlistController@toggle');
$router->get('/customer/wishlist-count', 'WishlistController@count');

//customer order
$router->get('/customer/orders', 'ProfileController@orders');
$router->post('/customer/add-product-review', 'ReviewController@add');
$router->get('/customer/download-invoice', 'InvoiceController@download');

//customer profile
$router->get('/customer/profile', 'ProfileController@fetchProfile');
$router->post('/customer/update-profile', 'ProfileController@updateProfile');


//admin
$router->get('/admin/dashboard', 'AdminController@dashboard');
$router->post('/admin/products', 'AdminController@products');
$router->get('/admin/fetch-all-products', 'AdminController@fetchAllProducts');
$router->get('/admin/delete-product', 'AdminController@deleteProduct');

// Dispatch the request
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
