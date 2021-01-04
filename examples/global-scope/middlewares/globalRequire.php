<?php
/**
 * List of files to require
 */
$requires = array();

/**
 * Returns a middleware replacing $req['require'] method
 * The replacement only registers files to require to $requires array
 * These files must be required manually from the global scope after 
 * calling App::execute
 * 
 * This technique allows the use of global keyword inside required files
 *
 * @return function
 */
function globalRequire() {
    return function (&$req, callable $next) {
        $req['require'] = function($file) {
            global $requires;
            array_push($requires, $file);
        };
        $next();
    };
}
?>
