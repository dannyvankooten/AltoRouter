<?php

require '../../AltoRouter.php';

class ListUrlConverter implements IUrlConverter {

	function __construct($delimiter=',', $coerce='string') {
		$this->delimiter = $delimiter;
		$this->coerce = $coerce;
	}

	function getRegexp() {
		return '[0-9a-zA-Z]+(' . $this->delimiter . '[0-9a-zA-Z]+)*';
	}
	
	function getUrl($data) {
		return join($this->delimiter, $data);
	}

	function getValue($url) {		
		return array_map(function($value) {
			settype($value, $this->coerce);
			return $value;
		}, explode($this->delimiter, $url));
	}
}

class RandomRangeUrlConverter implements IUrlConverter {

	function getRegexp() {
		return '\d+\.\.\d+';
	}
	
	function getUrl($data) {
		return sprintf("%d..%d", $data[0], $data[1]);
	}

	function getValue($url) {
		list($min, $max) = explode("..", $url);
		return rand($min, $max);
	}
}

$router = new AltoRouter();
$router->setBasePath('/AltoRouter/examples/urlconverter');
$router->addUrlConverters(
	array(
		'list' => new ListUrlConverter(',', 'int'),
		'random_range' => new RandomRangeUrlConverter()
	)
);

$router->map('GET', '/', 'Controller#home', 'home');
$router->map('GET', '/list/[list:values]/', 'Controller#list', 'list');
$router->map('GET', '/random/[random_range:value]/', 'Controller#random', 'random');

// match current request
$match = $router->match();
?>



<h1>URL converters</h1>

<h3>Current request: </h3>
<pre>
	Route name: <?php var_dump($match['name']); ?>
	Params (processed by URL converter): <?php var_dump($match['params']); ?>
</pre>

<h3>Try these requests: </h3>

<?php
	$link_home = $router->generate('home');
	$link_foo_values = $router->generate('list', array('values' => array(1, 2, 3)));
	$link_random_range = $router->generate('random', array('value' =>  array(0, 99)));
?>

<p><a href="<?php echo $link_home ?>">GET <?php echo $link_home ?></a></p>
<p><a href="<?php echo $link_foo_values ?>">GET <?php echo $link_foo_values ?></a></p>
<p><a href="<?php echo $link_random_range ?>">GET <?php echo $link_random_range ?></a></p>