<?php
// use composer autoload to include package files
require __DIR__ . '/vendor/autoload.php';

// alias \PHPRouter\App class
use \PHPRouter\App;

// create an App object, against which you will then register routes
$app = new App();

// register a new route to call when requested uri matches '/' in http method GET
$app->get('/', function(&$req, callable $next) {
    // do whatever you want this route to do
    print_r($req);

    // execute next route matching the requested uri
    $next();
});

// execute application mountpoints according to the requested uri
$app->execute();
