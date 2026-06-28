<?php

require_once __DIR__ . '/../app/Core/Router.php';

$router = new Router();

// Define application routes
$router->get('/', 'PostController@index');
$router->get('/posts', 'PostController@index');
$router->get('/posts/1', function () {
    echo "Viewing Post 1";
});

// Dispatch the request
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
