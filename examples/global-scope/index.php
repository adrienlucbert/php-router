<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require __DIR__ . '/vendor/autoload.php';

use \PHPRouter\App;

$app = new App();

// Enable global require middleware
require __DIR__ . '/middlewares/globalRequire.php';
$app->use('/', globalRequire());

$app->use('/', function (&$req, callable $next) {
    $req['require'](__DIR__ . '/views/index.php');
});

$app->execute();

// Require globally files that must be required (cf. global require middleware)
foreach($requires as $file) {
    require $file;
}