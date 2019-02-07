<?php

/**
 * Benchmark Altorouter
 *
 * Usage: php ./tests/benchmark.php <iterations>
 *
 * Options:
 *
 * <iterations>:
 * The number of routes to map & match. Defaults to 1000.
 */

require __DIR__ . '/../vendor/autoload.php';

global $argv;
$n = isset( $argv[1] ) ? intval( $argv[1] ) : 1000;

// generates a random request url
function random_request_url() {
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '/';

    // create random path of 5-20 characters
    for ($i = 0; $i < rand(5, 20); $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];

        if( rand(1, 10) === 1 ) {
           $randomString .= '/';
        }
    }

    // add dynamic route with 10% chance
    if ( rand(1, 10) === 1 ) {
       $randomString = rtrim( $randomString, '/' ) . '/[:part]';
    }

    return $randomString;
}

// generate a random request method
function random_request_method() {
    static $methods = array( 'GET', 'GET', 'POST', 'PUT', 'PATCH', 'DELETE' );
    $random_key = array_rand( $methods );
    return $methods[ $random_key ];
}

// prepare benchmark data
$requests = array();
for($i=0; $i<$n; $i++) {
    $requests[] = array(
        'method' => random_request_method(),
        'url' => random_request_url(),
    );
}

$router = new AltoRouter();

// map requests
$start = microtime(true);
foreach($requests as $r) {
    $router->map($r['method'], $r['url'], '');
}
$end = microtime(true);
$map_time = ($end - $start) * 1000;
echo sprintf( 'Map time: %.2f ms', $map_time ) . PHP_EOL;


// pick random route to match
$r = $requests[array_rand($requests)];

// match random known route
$start = microtime(true);
$router->match($r['url'], $r['method']);
$end = microtime(true);
$match_time_known_route = ($end - $start) * 1000;
echo sprintf( 'Match time (known route): %.2f ms', $match_time_known_route ) . PHP_EOL;

// match unexisting route
$start = microtime(true);
$router->match('/55-foo-bar', 'GET');
$end = microtime(true);
$match_time_unknown_route = ($end - $start) * 1000;
echo sprintf( 'Match time (unknown route): %.2f ms', $match_time_unknown_route ) . PHP_EOL;

// print totals
echo sprintf('Total time: %.2f seconds', ($map_time + $match_time_known_route + $match_time_unknown_route)) . PHP_EOL;
echo sprintf('Memory usage: %d KB', round( memory_get_usage() / 1024 )) . PHP_EOL;
echo sprintf('Peak memory usage: %d KB', round( memory_get_peak_usage( true ) / 1024 )) . PHP_EOL;



