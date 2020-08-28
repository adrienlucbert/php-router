<?php
/**
 * Returns a middleware checking if user is authenticated
 *
 * @return function
 */
function isAuth() {
    return function (&$req, callable $next) {
        if (!isset($_SESSION['logged'])) {
            http_response_code(401);
            return;
        }
        $next();
    };
}
?>
