<?php
session_start();

require_once('router/index.php');

echo '<pre>';

$app = new App();

require_once('./routers/home.php');
$app->use('/', $homeRouter);

require_once('./routers/auth.php');
$app->use('/auth', $authRouter);

$app->use('/admin', function(&$req, callable $next) {
    // this middleware will be used for every routes below /admin
    // may be used for access control
    if (!$_SESSION['logged']) {
        // if user is not logged in, don't call $next to stop here
        http_response_code(401);
        echo 'Restricted area!';
    } else {
        // if user is logged in, let him go through /admin
        $next();
    }
});

$app->get('/admin/me', function(&$req, callable $next) {
    require_once('views/admin.html');
});

$app->use('/', function(&$req, callable $next) {
    http_response_code(404);
    require_once('views/404.html');
});

$app->execute();

echo '</pre>';
?>
