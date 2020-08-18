<?php
require_once('./router/index.php');

$adminRouter = new Router();

$adminRouter->use('/', function(&$req, callable $next) {
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

$adminRouter->get('/me', function(&$req, callable $next) {
    require_once('views/admin.html');
});
?>
