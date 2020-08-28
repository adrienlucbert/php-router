<?php

namespace PHPRouter;

/**
 * Application utility class
 *
 * @since 1.0
 */
class App extends Router
{
    /**
     * Create a new Router object
     *
     * @since 1.0
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute application mountpoints according to requested uri
     * Also responsible for generating $req array
     *
     * @since 1.0
     */
    public function execute()
    {
        $req = array(
            'path' => $this->path, // current middleware uri
            'originalUrl' => $_SERVER['REQUEST_URI'], // requested uri
            'host' => $_SERVER['HTTP_HOST'], // server http host
            'ip' => $_SERVER['REMOTE_ADDR'], // remote client ip address
            'method' => $_SERVER['REQUEST_METHOD'] // http request method
        );
        $this->_exec($req);
    }
}
?>
