# AltoRouter  ![PHP status](https://github.com/dannyvankooten/AltoRouter/workflows/PHP/badge.svg) [![Latest Stable Version](https://poser.pugx.org/altorouter/altorouter/v/stable.svg)](https://packagist.org/packages/altorouter/altorouter) [![License](https://poser.pugx.org/altorouter/altorouter/license.svg)](https://packagist.org/packages/altorouter/altorouter)

AltoRouter is a small but powerful routing class, heavily inspired by [klein.php](https://github.com/chriso/klein.php/).

```php
$router = new AltoRouter();

// map homepage
$router->map('GET', '/', function() {
    require __DIR__ . '/views/home.php';
});

// dynamic named route
$router->map('GET|POST', '/users/[i:id]/', function($id) {
  $user = .....
  require __DIR__ . '/views/user/details.php';
}, 'user-details');

// echo URL to user-details page for ID 5
echo $router->generate('user-details', ['id' => 5]); // Output: "/users/5"
```

## Features

* Can be used with all HTTP Methods
* Dynamic routing with named route parameters
* Reversed routing
* Flexible regular expression routing (inspired by [Sinatra](http://www.sinatrarb.com/))
* Custom regexes

## Getting started

You need PHP >= 7.3 to use AltoRouter, although we highly recommend you [use an officially supported PHP version](https://secure.php.net/supported-versions.php) that is not EOL.

- [Install AltoRouter](https://dannyvankooten.github.io/AltoRouter//usage/install.html)
- [Rewrite all requests to AltoRouter](https://dannyvankooten.github.io/AltoRouter//usage/rewrite-requests.html)
- [Map your routes](https://dannyvankooten.github.io/AltoRouter//usage/mapping-routes.html)
- [Match requests](https://dannyvankooten.github.io/AltoRouter//usage/matching-requests.html)
- [Process the request your preferred way](https://dannyvankooten.github.io/AltoRouter//usage/processing-requests.html)

## Contributors
- [Danny van Kooten](https://github.com/dannyvankooten)
- [Koen Punt](https://github.com/koenpunt)
- [John Long](https://github.com/adduc)
- [Niahoo Osef](https://github.com/niahoo)

## License

MIT License

Copyright (c) 2012 Danny van Kooten <hi@dannyvankooten.com>

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
