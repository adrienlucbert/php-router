<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require __DIR__ . '/vendor/autoload.php';

use \PHPRouter\App;

$app = new App();

require './routers/index.php';
$app->use('/', $routers);

// fallback route: if none was matched before, throw 404 error
$app->use('/', function(&$req, callable $next) {
    http_response_code(404);
    require __DIR__ . '/views/404.html';
});

$app->execute();
