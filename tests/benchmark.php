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
function random_request_url( $length = 20 ) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz/';
    $charactersLength = strlen($characters);
    $randomString = '/';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// generate a random request method
function random_request_method() {
    static $methods = array( 'GET', 'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'GET|POST', 'PUT|POST' );
    $random_key = array_rand( $methods );
    return $methods[ $random_key ];
}


$router = new AltoRouter();

// map 1000 random routes
for( $i=0; $i < $n; $i++ ) {
    $router->map( random_request_method(), random_request_url(), function() {} );
}

// match 1000 random routes
$start = microtime(true);
for( $i=0; $i < $n; $i++ ) {
    $router->match( random_request_url(), random_request_method() );
}
$end = microtime( true );

// print execution time
echo "Time: " . number_format(( $end - $start ), 4 ). ' seconds' . PHP_EOL;
echo "Peak memory usage: " . ( memory_get_peak_usage( true ) / 1024 / 1024 ) . 'MB';