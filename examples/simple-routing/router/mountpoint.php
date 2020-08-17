<?php
require_once('lib/path.php');

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
     * Create a new MountPoint object
     *
     * @param string $path mount point path
     * @param [string] $methods mountpoint http methods
     * @param function $handler function called by execute method
     *
     * @since 1.0
     */
    function __construct($path, $methods, callable $handler, $parent = null)
    {
        $this->path = $path;
        $this->methods = $methods;
        $this->_handler = $handler;
        $this->_parent = $parent;
    }

    /**
     * Register mountpoints as children of this router
     *
     * @param string $path mountpoints path
     * @param [string] $methods mountpoints http methods
     * @param [MountPoint|function] $mountpoints middlewares and/or mountpoints
     * 
     * @since 1.0
     */
    protected function _register($path, $methods, ...$mountpoints)
    {
        foreach ($mountpoints as $mountpoint) {
            if (!($mountpoint instanceof MountPoint)) {
                $mountpoint = new MountPoint(\Path::join($this->path, $path), $methods, $mountpoint, $this);
            }
            array_push($this->_children, $mountpoint);
        }
    }
 
    /**
     * Finds next executable mount point
     *
     * @return function null if not found
     * 
     * @since 1.0
     */
    protected function _findNext($method, $path)
    {
        while ($this->_lastChildIndex < count($this->_children)) {
            $child = $this->_children[$this->_lastChildIndex];
            $matchesMethod = count(array_intersect([ '*', $method ], $child->methods)) != 0;
            if ($matchesMethod && \Path::glob_match($child->path, $path)) {
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

    /**
     * Execute mount point handler function
     *
     * @param array $req request data
     *
     * @since 1.0
     */
    public function _exec(&$req)
    {
        ($this->_handler)($req, function() use(&$req) {
            $next = $this->_findNext($req['method'], $req['path']);
            if ($next != null)
                $next->_exec($req);
        });
    }
}
?>
