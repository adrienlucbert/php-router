<?php
// include router/index.php which is responsible for including the files
// needed to work with PHP Router
require_once('router/index.php');

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
