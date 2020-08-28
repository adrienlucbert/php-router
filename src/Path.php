<?php

namespace PHPRouter;

/**
 * Path utility class
 *
 * @since 1.0
 */
class Path
{
    /**
     * Join paths with /
     *
     * @param [string] $paths paths to join
     * 
     * @return string
     *
     * @since 1.0
     */
    public static function join(...$paths)
    {
        $paths = array_filter($paths, function($path) {
            return $path !== '';
        });
        return preg_replace('#/+#', '/', join('/', $paths));
    }

    /**
     * Normalize a path, removing useless or ambiguous
     *
     * Removes leading slash (/)
     * Removes duplicate slashes (//)
     *
     * @param string $path path to normalize
     *
     * @return string
     *
     * @since 1.0
     */
    public static function normalize($path)
    {
        if ($path !== '/')
            $path = rtrim($path, '/');
        $path = str_replace('//', '/', $path);
        return $path;
    }

    /**
     * Determines if a path matches a glob pattern
     *
     * @param string $pattern pattern to match
     * @param string $path path to match
     * @param bool $strict if false, matches strings that begin with the 
     * pattern, if true, matches strings that match the pattern exactly
     *
     * @return bool
     *
     * @since 1.0
     */
    public static function glob_match($pattern, $path, $strict = false)
    {
        $expr = Path::normalize($pattern);
        $path = Path::normalize($path);
        $expr = preg_replace_callback('/[\\\\^$.[\\]|()?*+{}\\-\\/]/', function($matches) {
            switch ($matches[0]) {
            case '*':
                return '.*';
            case '?':
                return '.';
            default:
                return '\\'.$matches[0];
            }
        }, $expr);
        $expr = '/'.$expr.($strict ? '$' : '').'/i';
        return (bool)preg_match($expr, $path);
    }
}
?>
