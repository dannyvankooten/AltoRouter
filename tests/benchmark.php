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
 *
 * @return string
 */
function randomRequestUrl($length = 20)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz/';
    $charactersLength = strlen($characters);
    $randomString = '/';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
/**
 * Generate a random request method.
 *
 * @return string
 */
function randomRequestMethod()
{
    static $methods = array(
        'GET',
        'POST',
        'PUT',
        'PATCH',
        'DELETE',
        'GET|POST',
        'PUT|POST'
    );
    $random_key = array_rand($methods);
    return $methods[ $random_key ];
}
$router = new AltoRouter();
// map 1000 random routes
for ($i=0; $i < $n; $i++) {
    $router->map(
        randomRequestMethod(),
        randomRequestUrl(),
        function () {
        }
    );
}
// match 1000 random routes
$start = microtime(true);
for ($i=0; $i < $n; $i++) {
    $router->match(
        randomRequestUrl(),
        randomRequestMethod()
    );
}
$end = microtime(true);
// print execution time
echo "Time: "
    . number_format(($end - $start), 4)
    . ' seconds'
    . PHP_EOL;
echo "Peak memory usage: "
    . (memory_get_peak_usage(true) / 1024 / 1024)
    . 'MB'
    . PHP_EOL;
