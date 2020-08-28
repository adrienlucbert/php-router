<?php
use \PHPRouter\Router;

$homeRouter = new Router();

$homeRouter->get('/', function(&$req, callable $next) {
    require __DIR__ . '/../views/home.html';
});
