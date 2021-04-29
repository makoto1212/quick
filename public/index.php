<?php
require __DIR__ . '/../vendor/autoload.php';
ini_set('log_errors', 'on');
ini_set('error_log', __DIR__ . '/../log/error.log');

$base = '/index.php';
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) use($base) {
    $r->addRoute('GET', "{$base}/hello", 'App\Http\Controllers\HelloController@index');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        header('HTTP/1.0 404 Not Found');
        echo '404';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        header('HTTP/1.0 405 Method Not Allowed');
        echo '405';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        // ... call $handler with $vars
        //echo $handler($vars);

        list($namespace, $action) = explode('@', $handler);
        echo (new $namespace)->$action();
        break;
}

