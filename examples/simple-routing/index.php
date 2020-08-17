<?php
require_once('router/index.php');

$app = new App();

$app->use('*', function($req, callable $next) {
    // this middleware will be executed before every other
    // may be used for access control or logging

    // execute next route matching the requested uri
    $next();
});

$app->get('/home', function(&$req, callable $next) {
    require_once('views/home.html');
});

$app->use('/admin', function(&$req, callable $next) {
    // this middleware will be used for every routes below /admin
    // may be used for access control
    $next();
});

$app->get('/admin', function(&$req, callable $next) {
    require_once('views/admin.html');
});

$app->use('*', function(&$req, callable $next) {
    // this middleware will be executed after every other, if middlewares chain 
    // was not broken before ($next not called in a previous middleware)

    // it may be used for handling 404 not found error
    http_response_code(404);
    require_once('views/404.html');
});

$app->execute();
?>
