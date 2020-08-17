<?php
require_once('./router/mountpoint.php');

/**
 * Router
 *
 * @since 1.0
 */
class Router extends MountPoint
{
    /**
     * Create a new Router object
     *
     * @param string $path router base path
     */
    function __construct($path = '/')
    {
        parent::__construct($path, ['*'], function(&$req, $next) {
            $next();
        });
    }

  /**
     * Register mountpoints as children of this router for any http method
     *
     * @param string $path mountpoints path
     * @param [MountPoint|function] $mountpoints middlewares and/or mountpoints
     *
     * @since 1.0
     */
    public function use($path, ...$mountpoints) {
        $this->_register($path, ['*'], ...$mountpoints);
    }

     /**
     * Register mountpoints as children of this router for GET http method
     *
     * @param string $path mountpoints path
     * @param [MountPoint|function] $mountpoints middlewares and/or mountpoints
     *
     * @since 1.0
     */
    public function get($path, ...$mountpoints) {
        $this->_register($path, ['GET'], ...$mountpoints);
    }

     /**
     * Register mountpoints as children of this router for POST http method
     *
     * @param string $path mountpoints path
     * @param [MountPoint|function] $mountpoints middlewares and/or mountpoints
     *
     * @since 1.0
     */
    public function post($path, ...$mountpoints) {
        $this->_register($path, ['POST'], ...$mountpoints);
    }
}
?>
