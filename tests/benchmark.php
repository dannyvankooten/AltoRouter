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
    $router->map($r['method'], $r['url'], function(){});
}
$end = microtime(true);
$map_time = $end - $start;
echo "Map time: " . number_format($map_time, 4). ' seconds' . PHP_EOL;

// shuffle requests
shuffle($requests);

// match 1000 random routes
$start = microtime(true);
foreach($requests as $r) {
    $router->match($r['url'], $r['method']);
}
$end = microtime( true );
$match_time = $end - $start;
echo "Match time: " . number_format($match_time, 4). ' seconds' . PHP_EOL;

// print totals
echo "Total time: " . number_format(($map_time + $match_time), 4). ' seconds' . PHP_EOL;
echo "Memory usage: " . round( memory_get_usage() / 1024 ) . 'KB' . PHP_EOL;
echo "Peak memory usage: " . round( memory_get_peak_usage( true ) / 1024 ) . 'KB' . PHP_EOL;
