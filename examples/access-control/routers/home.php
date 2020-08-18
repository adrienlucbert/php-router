<?php
require_once('./router/index.php');

$homeRouter = new Router();

$homeRouter->get('/', function(&$req, callable $next) {
    require_once('views/home.html');
});
?>
