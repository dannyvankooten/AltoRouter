# AltoRouter 
AltoRouter is a small but powerful routing class for PHP 5.3+, heavily inspired by [klein.php](https://github.com/chriso/klein.php/).

* Dynamic routing with named parameters
* Reversed routing
* Flexible regular expression routing (inspired by [Sinatra](http://www.sinatrarb.com/))

## Getting started

1. PHP 5.3.x is required
2. Setup URL rewriting so that all requests are handled by **index.php**
3. Create an instance of AltoRouter, map your routes and match a request.
4. Have a look at the supplied example file for a better understanding on how to use AltoRouter(index.php).

## Routing
```php
$router = new AltoRouter();
$router->setBasePath('/AltoRouter');

// mapping routes
$router->map('GET|POST','/', 'home#index', 'home');
$router->map('GET','/users/', array('c' => 'UserController', 'a' => 'ListAction'));
$router->map('GET','/users/[i:id]', 'users#show', 'users_show');
$router->map('POST','/users/[i:id]/[delete|update:action]', 'usersController#doAction', 'users_do');


// reversed routing
$router->generate('users_show', array('id' => 5));

```

You can use the following limits on your named parameters. AltoRouter will create the correct regexes.
```php
    *                    // Match all request URIs
    [i]                  // Match an integer
    [i:id]               // Match an integer as 'id'
    [a:action]           // Match alphanumeric characters as 'action'
    [h:key]              // Match hexadecimal characters as 'key'
    [:action]            // Match anything up to the next / or end of the URI as 'action'
    [create|edit:action] // Match either 'create' or 'edit' as 'action'
    [*]                  // Catch all (lazy, stops at the next trailing slash)
    [*:trailing]         // Catch all as 'trailing' (lazy)
    [**:trailing]        // Catch all (possessive - will match the rest of the URI)
    .[:format]?          // Match an optional parameter 'format' - a / or . before the block is also optional

Some more complicated examples

    /posts/[*:title][i:id]     // Matches "/posts/this-is-a-title-123"
    /output.[xml|json:format]? // Matches "/output", "output.xml", "output.json"
    /[:controller]?/[:action]? // Matches the typical /controller/action format

```

## Additional info
If you like AltoRouter, you might also like [PHP Router](//github.com/dannyvankooten/PHP-Router).

## License

(MIT License)

Copyright (c) 2012 Danny van Kooten <dannyvankooten@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
