<?php
use \PHPRouter\Router;

$routers = new Router();

require __DIR__ . '/home.php';
$routers->use('/', $homeRouter);

require __DIR__ . '/auth.php';
$routers->use('/auth', $authRouter);

require __DIR__ . '/admin.php';
$routers->use('/admin', $adminRouter);
