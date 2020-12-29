<?php

namespace PHPRouter;

/**
 * Executable mount point (router, middleware)
 *
 * @since 1.0
 */
class MountPoint
{
    /**
     * MountPoint path
     *
     * @since 1.0
     */
    public $path = null;

    /**
     * MountPoint http methods
     *
     * @since 1.0
     */
    public $methods = null;

    /**
     * MountPoint handler function
     *
     * @since 1.0
     */
    private $_handler = null;

    /**
     * Parent router
     *
     * @since 1.0
     */
    private $_parent = null;

    /**
     * Children routers
     *
     * @since 1.0
     */
    private $_children = [];

    /**
     * Last child of $_children called via _exec method
     *
     * @since 1.0
     */
    private $_lastChildIndex = 0;

    /**
     * Routing strict mode
     * In strict mode, the mountpoint is executable only if its path is 
     * strictly matched by the requested uri
     *
     * @since 1.0
     */
    private $_strict = true;

    /**
     * Create a new MountPoint object
     *
     * @param string $path mount point path
     * @param [string] $methods mountpoint http methods
     * @param function $handler function called by execute method
     * @param bool $strict mountpoint strict mode
     * @param MountPoint $parent parent mountpoint
     *
     * @since 1.0
     */
    function __construct($path, $methods, callable $handler, $strict = true, $parent = null)
    {
        $this->path = $path;
        $this->methods = $methods;
        $this->_handler = $handler;
        $this->_strict = $strict;
        $this->_parent = $parent;
    }

    /**
     * Register mountpoints as children of this router
     *
     * @param string $path mountpoints path
     * @param [string] $methods mountpoints http methods
     * @param bool $strict mountpoints strict mode
     * @param [MountPoint|function] $items middlewares and/or mountpoints
     * 
     * @since 1.0
     */
    protected function _register($path, $methods, $strict, ...$items)
    {
        foreach ($items as $item) {
            if (!($item instanceof MountPoint)) {
                $item = new MountPoint($path, $methods, $item, $strict, $this);
            } else {
                $item->path = $path;
                $item->methods = $methods;
                $item->_strict = $strict;
                $item->_parent = $this;
            }
            array_push($this->_children, $item);
        }
    }
 
    /**
     * Finds next executable mount point
     *
     * @param string $method request http method
     * @param string $path request uri
     *
     * @return MountPoint null if not found
     * 
     * @since 1.0
     */
    protected function _findNext($method, $path)
    {
        while ($this->_lastChildIndex < count($this->_children)) {
            $child = $this->_children[$this->_lastChildIndex];
            $matchesMethod = count(array_intersect([ '*', $method ], $child->methods)) != 0;
            $matchesPath = Path::glob_match($child->_getFullPath(), $path, $child->_strict);
            if ($matchesMethod && $matchesPath) {
                ++$this->_lastChildIndex;
                return $child;
            }
            ++$this->_lastChildIndex;
        }
        if ($this->_parent != null) {
            return $this->_parent->_findNext($method, $path);
        }
        ++$this->_lastChildIndex;
        return null;
    }

    protected function _getFullPath()
    {
        if ($this->_parent == null)
            return $this->path;
        return Path::join($this->_parent->_getFullPath(), $this->path);
    }

    /**
     * Execute mount point handler function
     *
     * @param array $req request data
     *
     * @since 1.0
     */
    protected function _exec(&$req)
    {
        $req['path'] = preg_replace('/\*/', '', $this->_getFullPath());
        ($this->_handler)($req, function() use(&$req) {
            $next = $this->_findNext($req['method'], $req['originalUrl']['path']);
            if ($next != null)
                $next->_exec($req);
        });
    }
}
?>
