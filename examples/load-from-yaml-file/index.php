<?php

require '../../AltoRouter.php';
	
// make sure to load symfony/yaml component via composer
// and load it with composer autoloader 
//require 'vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

$router = new AltoRouter();
$router->setBasePath('/AltoRouter/examples/load-from-yaml-file');

// load and parse your routes from an external yaml file 
$yaml_file = 'routes.yaml';
$routes = Yaml::parse( file_get_contents($yaml_file) );
foreach ( $routes as $route_name => $params ) {
	$router->map( $params[0], $params[1], $params[2], $route_name );
} 

// match current request
$match = $router->match();
?>
<h1>AltoRouter</h1>

<h3>Current request: </h3>
<pre>
	Target: <?php var_dump($match['target']); ?>
	Params: <?php var_dump($match['params']); ?>
	Name: 	<?php var_dump($match['name']); ?>
</pre>

<h3>Try these requests: </h3>
<p><a href="<?php echo $router->generate('home'); ?>">GET <?php echo $router->generate('home'); ?></a></p>
<p><a href="<?php echo $router->generate('users_show', array('id' => 5)); ?>">GET <?php echo $router->generate('users_show', array('id' => 5)); ?></a></p>
<p><form action="<?php echo $router->generate('users_do', array('id' => 10, 'action' => 'update')); ?>" method="post"><button type="submit"><?php echo $router->generate('users_do', array('id' => 10, 'action' => 'update')); ?></button></form></p>
