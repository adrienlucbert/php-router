<?php

namespace PHPRouter;

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
    function __construct()
    {
        parent::__construct(null, ['*'], function(&$req, $next) {
            $next();
        }, false, null);
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
        $this->_register($path, ['*'], false, ...$mountpoints);
    }

    /**
     * List of supported http methods
     *
     * @since 1.0
     */
    private $_methods = [
        'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'COPY', 'HEAD', 'OPTIONS',
        'LINK', 'UNLINK', 'PURGE', 'LOCK', 'UNLOCK', 'PROPFIND', 'VIEW'
    ];

    public function __call($func, $params) {
        $method = strtoupper($func);
        if (in_array(strtoupper($func), $this->_methods)) {
            $path = $params[0];
            $mountpoints = array_slice($params, 1);
            $this->_register($path, [$method], true, ...$mountpoints);
        }
    }
}
?>
