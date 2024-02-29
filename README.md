# EdgeAltoRouter  ![PHP status](https://github.com/Acksop/EdgeAltoRouter/workflows/PHP/badge.svg)

EdgeAltoRouter is a small but powerful routing class, forked from [dannyvankooten.php](https://github.com/dannyvankooten/AltoRouter/) with an important addition : a config model file.

```php
$router = new EdgeAltoRouter(configModelUrl : __DIR__ . DIRECTORY_SEPARATOR . 'route.config');
$router->setBasePath(BASE_PATH);

$match = $router->match();

/*
 * $match may is like :
 * [
 *   'target' => 'home#index',
 *   'params' => [],
 *   'name' => 'home'
 * ]
 */

if( is_array($this->match) && is_callable( $this->match['target'] ) ) {
    call_user_func_array( $this->match['name'], $this->match['params'] ); 
} else {
    // no route was matched
    header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}


// echo URL to user-details page for ID 5
echo $router->generate('home'); // Output: "home#index"
```

## Features

* Can be used with all HTTP Methods
* Dynamic routing with named route parameters
* Reversed routing
* Flexible regular expression routing (inspired by [Sinatra](http://www.sinatrarb.com/))
* Custom regexes
* Support a config file who is like Yaml

## Getting started

You need PHP >= 8.0 to use EdgeAltoRouter, although we highly recommend you [use an officially supported PHP version](https://secure.php.net/supported-versions.php) that is not EOL.

## Offical Documentation of the forked AltoRouter

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
- [Emmanuel ROY](https://github.com/acksop)

## License

MIT License

Copyright (c) 2012 Danny van Kooten <hi@dannyvankooten.com>
Addition/modification 2024 ROY Emmanuel <emmanuel.roy@infoartsmedia.fr>

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
