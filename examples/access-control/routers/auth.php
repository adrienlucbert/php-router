<?php
use \PHPRouter\Router;

$authRouter = new Router();

// weak login for demo purposes

$authRouter->get('/login', function(&$req, callable $next) {
    $_SESSION['logged'] = true;
});

$authRouter->get('/logout', function(&$req, callable $next) {
    $_SESSION['logged'] = false;
});
?>
