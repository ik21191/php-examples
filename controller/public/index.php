<?php
// 1. Setup a simple PSR-4 style autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../src/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// 2. Extract and sanitize the path from the URL request
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = trim($requestUri, '/');

// 3. Define explicit valid routes maps
$routes = [
    '' => [\App\Controllers\HomeController::class, 'index'],
    'about' => [\App\Controllers\AboutController::class, 'index']
];

// 4. Dispatch mechanism: Match requested path against map
if (array_key_exists($path, $routes)) {
    [$controllerClass, $method] = $routes[$path];
    
    $controllerInstance = new $controllerClass();
    $controllerInstance->$method();
} else {
    // 5. Fallback 404 response handler
    http_response_code(404);
    echo "<h1>404 Something went wrong !</h1>";
}
