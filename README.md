# PHP Router

This project is a minimal router framework for PHP, inspired by
[ExpressJS](https://expressjs.com/).

## Installation

Use composer to manage and dependencies and download PHP Router.

```
composer require adrienlucbert/php-router
```

## Example

> :information_source: find more examples under `/examples` directory

> :warning: You may also use with a `.htaccess` file redirecting all requests
to a single file. This file will be responsible for describing routes: we call
it `index.php` for the purpose of this example, but you may call it as you wish,
just make sure the `.htaccess` file redirects to it.  

```php
<?php
// use composer autoload to include package files
require __DIR__ . '/vendor/autoload.php';

// alias \PHPRouter\App class
use \PHPRouter\App;

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
