<?php

/**
 * Benchmark Altorouter
 *
 * Usage: php ./tests/benchmark-parse-api.php <iterations>
 *
 * Options:
 *
 * <iterations>:
 * The number of routes to map & match. Defaults to 1000.
 */

require __DIR__ . '/../vendor/autoload.php';
$routes = [
	["POST", "/1/classes/[a:className]"],
	["GET", "/1/classes/[a:className]/[i:objectId]"],
	["PUT", "/1/classes/[a:className]/[i:objectId]"],
	["GET", "/1/classes/[a:className]"],
	["DELETE", "/1/classes/[a:className]/[i:objectId]"],

	// Users
	["POST", "/1/users"],
	["GET", "/1/login"],
	["GET", "/1/users/[i:objectId]"],
	["PUT", "/1/users/[i:objectId]"],
	["GET", "/1/users"],
	["DELETE", "/1/users/[i:objectId]"],
	["POST", "/1/requestPasswordReset"],

	// Roles
	["POST", "/1/roles"],
	["GET", "/1/roles/[i:objectId]"],
	["PUT", "/1/roles/[i:objectId]"],
	["GET", "/1/roles"],
	["DELETE", "/1/roles/[i:objectId]"],

	// Files
	["POST", "/1/files/:fileName"],

	// Analytics
	["POST", "/1/events/[a:eventName]"],

	// Push Notifications
	["POST", "/1/push"],

	// Installations
	["POST", "/1/installations"],
	["GET", "/1/installations/[i:objectId]"],
	["PUT", "/1/installations/[i:objectId]"],
	["GET", "/1/installations"],
	["DELETE", "/1/installations/[i:objectId]"],

	// Cloud Functions
	["POST", "/1/functions"],
];
$total_time = 0;
$router = new AltoRouter();

// map requests
$start = microtime(true);
foreach ($routes as $r) {
    $router->map($r[0], $r[1], '');
}
$end = microtime(true);
$time = $end - $start;
$total_time += $time;
echo sprintf('Map time: %.3f ms', $time * 1000) . PHP_EOL;

// match a static route
$start = microtime(true);
$router->match('/1/login', 'GET');
$end = microtime(true);
$time = $end - $start;
$total_time += $time;
echo sprintf('Match time (existing route, no params): %.3f ms', $time * 1000) . PHP_EOL;

// match a route with 1 parameter
$start = microtime(true);
$res = $router->match('/1/classes/foo', 'GET');
$end = microtime(true);
$time = $end - $start;
$total_time += $time;
echo sprintf('Match time (existing route, 1 param): %.3f ms', $time * 1000) . PHP_EOL;

// match a route with 2 parameters
$start = microtime(true);
$res = $router->match('/1/classes/foo/500', 'GET');
$end = microtime(true);
$time = $end - $start;
$total_time += $time;
echo sprintf('Match time (existing route, 2 params): %.3f ms', $time * 1000) . PHP_EOL;


// match unexisting route
$start = microtime(true);
$router->match('/55-foo-bar', 'GET');
$end = microtime(true);
$time = $end - $start;
$total_time += $time;
echo sprintf('Match time (unexisting route): %.3f ms', $time * 1000) . PHP_EOL;

// print totals
echo sprintf('Total time: %.3f ms', $total_time * 1000) . PHP_EOL;
echo sprintf('Memory usage: %d KB', round(memory_get_usage() / 1024)) . PHP_EOL;
echo sprintf('Peak memory usage: %d KB', round(memory_get_peak_usage(true) / 1024)) . PHP_EOL;
