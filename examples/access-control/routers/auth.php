<?php
require_once('./router/index.php');

$authRouter = new Router();

// weak login for demo purposes

$authRouter->post('/login', function(&$req, callable $next) {
    $_SESSION['logged'] = true;
});

$authRouter->post('/logout', function(&$req, callable $next) {
    $_SESSION['logged'] = false;
});
?>
