<?php
use \PHPRouter\Router;

$homeRouter = new Router();

$homeRouter->get('/', function(&$req, callable $next) {
    $req['require'](__DIR__ . '/../views/home.html');
});
