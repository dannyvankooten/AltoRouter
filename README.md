# AltoRouter [![Build Status](https://api.travis-ci.org/dannyvankooten/AltoRouter.png)](http://travis-ci.org/dannyvankooten/AltoRouter) [![Latest Stable Version](https://poser.pugx.org/altorouter/altorouter/v/stable.svg)](https://packagist.org/packages/altorouter/altorouter) [![License](https://poser.pugx.org/altorouter/altorouter/license.svg)](https://packagist.org/packages/altorouter/altorouter) [![Code Climate](https://codeclimate.com/github/dannyvankooten/AltoRouter/badges/gpa.svg)](https://codeclimate.com/github/dannyvankooten/AltoRouter) [![Test Coverage](https://codeclimate.com/github/dannyvankooten/AltoRouter/badges/coverage.svg)](https://codeclimate.com/github/dannyvankooten/AltoRouter)
AltoRouter is a small but powerful routing class for PHP 5.4+, heavily inspired by [klein.php](https://github.com/chriso/klein.php/).

```php
$router = new AltoRouter();

// map homepage
$router->map( 'GET', '/', function() {
    require __DIR__ . '/views/home.php';
});

// map users details page
$router->map( 'GET|POST', '/users/[i:id]/', function( $id ) {
  $user = .....
  require __DIR__ . '/views/user/details.php';
});
```
AltoRouter used as trait in your class
```php
// Create you class 
class mySuperClass {
	// Use AltoRouter in you superclass
	use AltoRouterTrait;
}


$Imyclass = new mySuperClass();
$Imyclass->map('GET|POST','/', 'home#index', 'home');
$Imyclass->map('GET','/users/', array('c' => 'UserController', 'a' => 'ListAction'));
$Imyclass->map('GET','/users/[i:id]', 'users#show', 'users_show');
$Imyclass->map('POST','/users/[i:id]/[delete|update:action]', 'usersController#doAction', 'users_do');

// match current request
$match = $Imyclass->match();

```

## Features

* Can be used with all HTTP Methods
* Dynamic routing with named route parameters
* Reversed routing
* Flexible regular expression routing (inspired by [Sinatra](http://www.sinatrarb.com/))
* Custom regexes
* Can be used as trait in php 5.4

## Getting started

You need PHP >= 5.4 to use AltoRouter.

- [Install AltoRouter](http://altorouter.com/usage/install.html)
- [Rewrite all requests to AltoRouter](http://altorouter.com/usage/rewrite-requests.html)
- [Map your routes](http://altorouter.com/usage/mapping-routes.html)
- [Match requests](http://altorouter.com/usage/matching-requests.html)
- [Process the request your preferred way](http://altorouter.com/usage/processing-requests.html)

## Contributors
- [Danny van Kooten](https://github.com/dannyvankooten)
- [Koen Punt](https://github.com/koenpunt)
- [John Long](https://github.com/adduc)
- [Niahoo Osef](https://github.com/niahoo)

## License

(MIT License)

Copyright (c) 2012-2015 Danny van Kooten <hi@dannyvankooten.com>

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
