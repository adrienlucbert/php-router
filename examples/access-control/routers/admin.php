<?php
use \PHPRouter\Router;

require_once __DIR__ . '/../middlewares/isAuth.php';

$adminRouter = new Router();

$adminRouter->use('/', isAuth());

$adminRouter->get('/', function(&$req, callable $next) {
    require __DIR__ . '/../views/admin.html';
});
