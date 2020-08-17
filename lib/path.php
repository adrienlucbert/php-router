<?php
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
     * Determines if a path matches a glob pattern
     *
     * @param string $pattern pattern to match
     * @param string $path path to match
     *
     * @return bool
     *
     * @since 1.0
     */
    public static function glob_match($pattern, $path)
    {
        $expr = preg_replace_callback('/[\\\\^$.[\\]|()?*+{}\\-\\/]/', function($matches) {
            switch ($matches[0]) {
            case '*':
                return '.*';
            case '?':
                return '.';
            default:
                return '\\'.$matches[0];
            }
        }, $pattern);
        $expr = '/'.$expr.'/i';
        return (bool)preg_match($expr, $path);
    }
}
?>
