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
     * List of existing php file extensions
     * 
     * @since 1.1
     */
    static private $_PHPExtensions = [
        'php', 'php3', 'php4', 'php5', 'php7', 'phps', 'phtml'
    ];

    /**
     * Checks if a given file extension corresponds to a PHP file
     * 
     * @return bool
     * 
     * @since 1.1
     */
    static private function _isPHP($ext) {
        return in_array($ext, self::$_PHPExtensions);
    }

    /**
     * Ordered list of resources to look for when client requests a directory
     * 
     * @since 1.1
     */
    static private $_directoryIndex = [
        'index.php', 'index.html', 'index.html'
    ];

    /**
     * Returns the correct file path of a resource and checks if it exists
     * If it is a directory, look for a directory index in it
     * 
     * @param string $path resource path
     * 
     * @return string the file path (as given, or directory index), or null 
     * if the resource cannot be found
     * 
     * @since 1.1
     */
    static private function resolveFile($path) {
        if (!file_exists($path)) {
            return null;
        }
        if (is_dir($path)) {
            foreach(self::$_directoryIndex as $index) {
                $index_path = Path::join($path, $index);
                if (file_exists($index_path) && !is_dir($index_path)) {
                    return $index_path;
                }
            }
            return null;
        }
        return $path;
    }

    /**
     * Determines the file type and sets the Content-Type response 
     * header accordingly
     * 
     * @param string $file file path
     * 
     * @since 1.1
     */
    static private function _setContentType($file) {
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mime_overrides = array(
            'css' => 'text/css',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml'
        );
        foreach(self::$_PHPExtensions as $phpext) {
            $mime_overrides[$phpext] = 'text/html';
        }
        if (array_key_exists($ext, $mime_overrides)) {
            $mime = $mime_overrides[$ext];
        } else {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file);
        }
        header('Content-Type: ' . $mime);
    }

    /**
     * Return middleware serving files in $path directory
     *
     * @param string $path static files directory
     *
     * @return function
     *
     * @since 1.0
     */
    static public function static($path) {
        return function(&$req, callable $next) use ($path){
            $php_exts = ['php', 'php3', 'php4', 'php5', 'php7', 'phps', 'phtml'];
            $file = preg_replace("/^" . preg_quote($req['path'], '/') . "/", '', $req['originalUrl'], 1);
            $file = Path::join($path, $file);
            $file = self::resolveFile($file);
            if (!$file) {
                http_response_code(404);
                return;
            }
            self::_setContentType($file);
            require($file);
        };
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
    static private $_methods = [
        'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'COPY', 'HEAD', 'OPTIONS',
        'LINK', 'UNLINK', 'PURGE', 'LOCK', 'UNLOCK', 'PROPFIND', 'VIEW'
    ];

    public function __call($func, $params) {
        $method = strtoupper($func);
        if (in_array(strtoupper($func), self::$_methods)) {
            $path = $params[0];
            $mountpoints = array_slice($params, 1);
            $this->_register($path, [$method], true, ...$mountpoints);
        }
    }
}
?>
