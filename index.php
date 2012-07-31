<?php

require 'AltoRouter.php';

$router = new AltoRouter();
$router->setBasePath('/AltoRouter');
$router->map('GET|POST','/', 'home#index', array('name' => 'home'));
$router->map('GET','/users/', array('c' => 'UserController', 'a' => 'ListAction'));
$router->map('GET','/users/[i:id]', 'users#show', array('name' => 'users_show'));
$router->map('POST','/users/[i:id]/[delete|update:action]', 'usersController#doAction', array('name' => 'users_do'));

// match current request
$match = $router->match();
?>
<h1>AltoRouter</h3>

<h3>Current request: </h3>
<pre>
	Target: <?php var_dump($match['target']); ?>
	Params: <?php var_dump($match['params']); ?>
</pre>

<h3>Try these requests: </h3>
<p><a href="<?php echo $router->generate('home'); ?>">GET <?php echo $router->generate('home'); ?></a></p>
<p><a href="<?php echo $router->generate('users_show', array('id' => 5)); ?>">GET <?php echo $router->generate('users_show', array('id' => 5)); ?></a></p>
<p><form action="<?php echo $router->generate('users_do', array('id' => 10, 'action' => 'update')); ?>" method="post"><button type="submit"><?php echo $router->generate('users_do', array('id' => 10, 'action' => 'update')); ?></button></form></p>