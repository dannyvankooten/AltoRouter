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
 *
 * PHP Version 5
 *
 * @category Benchmark
 * @package  AltoRouter
 * @author   Danny van Kooten <no@email.given>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/dannyvankooten/AltoRouter
 */
/**
 * Benchmark Altorouter
 *
 * Usage: php ./tests/benchmark.php <iterations>
 *
 * Options:
 *
 * <iterations>:
 * The number of routes to map & match. Defaults to 1000.
 *
 * @category Benchmark
 * @package  AltoRouter
 * @author   Danny van Kooten <no@email.given>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/dannyvankooten/AltoRouter
 */
require __DIR__ . '/../vendor/autoload.php';
global $argv;
$n = isset($argv[1]) ? intval($argv[1]) : 1000;
/**
 * Generates a random request url.
 *
 * @param int $length Length of the url.
 * @param int $n      Total of routes to make.
 *
 * @return string
 */
function randomRequestUrl($length = 20, $n = 1000)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz/';
    $charactersLength = strlen($characters);
    $randomString = '/';
    $routes = array();
    for ($h = 0; $h < $n; $h++) {
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $routes[] = $randomString;
        $randomString = '/';
    }
    return $routes;
}
$methods = array(
    'GET',
    'POST',
    'PUT',
    'PATCH',
    'DELETE',
    'GET|POST',
    'PUT|POST'
);
while (count($methods) <= $n) {
    $methods = array_merge(
        $methods,
        $methods
    );
}
$methods = array_slice($methods, 0, $n);
$randkeys = array_rand($methods, $n);
$routes = randomRequestUrl(4, $n);
$router = new AltoRouter\AltoRouter();
// map 1000 random routes
$start = microtime(true);
for ($i=0; $i < $n; $i++) {
    $router->map(
        $methods[$randkeys[$i]],
        $routes[$i],
        'string'
    );
}
$end = microtime(true);
// print execution time
echo "Time: Mapping "
    . number_format(($end - $start), 4)
    . ' seconds'
    . PHP_EOL;
echo "Peak memory usage: "
    . (memory_get_peak_usage(true) / 1024)
    . 'KB'
    . PHP_EOL;
// match 1000 random routes
$start = microtime(true);
for ($i=0; $i < $n; $i++) {
    $router->match(
        $routes[$i],
        $methods[$randkeys[$i]]
    );
}
$end = microtime(true);
// print execution time
echo "Time: Matching "
    . number_format(($end - $start), 4)
    . ' seconds'
    . PHP_EOL;
echo "Peak memory usage: "
    . (memory_get_peak_usage(true) / 1024)
    . 'KB'
    . PHP_EOL;
