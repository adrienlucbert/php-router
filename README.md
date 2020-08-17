# PHP Router

This project is a minimal router framework for PHP, inspired by
[ExpressJS](https://expressjs.com/).

## Examples

You can find examples in the `/examples` directory.

## Usage

You may copy `/lib` and `/router` directories in your project's root directory
in order to use PHP Router.

You may also use with a `.htaccess` file redirecting all requests to a single
file. This file will be responsible for describing routes: we we call it
`index.php` for the purpose of this example, but you may call it as you wish,
just make sure the `.htaccess` file redirects to it.  
To see an example `.htaccess`, see examples in the `/examples` directory.

See a basic routing example herebelow:

```php
<?php
// include router/index.php which is responsible for including the files
// needed to work with PHP Router
require_once('router/index.php');

// create an App object, against which you will then register routes
$app = new App();

// register a new route to call when requested uri matches '/' in http method GET
$app->get('/', function(&$req, callable $next) {
    // do whatever you want this route to do
    print_r($req);

    // execute next route matching the requested uri
    $next();
});

// execute application mountpoints according to the requested uri
$app->execute();
```

