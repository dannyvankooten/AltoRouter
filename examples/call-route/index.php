<?php

require '../../AltoRouter.php';

//include our example controller
require 'example_controller.php';
	
$router = new AltoRouter();
$router->setBasePath('/AltoRouter/examples/call-route');
$router->map('GET|POST', '/', 'ExampleController#myMethodIndex', 'users_index');
$router->map('GET|POST', '/users/[i:id]/[:action]', 'ExampleController#myMethod', 'users_do');

// match current request
$match = $router->match();

?>

<h1>AltoRouter</h3>

<h3>Try these requests: </h3>
<p>
	<a href="<?php echo $router->generate('users_index'); ?>">
		GET <?php echo $router->generate('users_index'); ?>
	</a>
</p>

<p>
	<a href="<?php echo $router->generate('users_do', array('id' => 7,'action' => 'view')); ?>">
		GET <?php echo $router->generate('users_do', array('id' => 7,'action' => 'view')); ?>
	</a>
</p>
<p>
	<a href="<?php echo $router->generate('users_do', array('id' => 17,'action' => 'edit')); ?>">
		GET <?php echo $router->generate('users_do', array('id' => 17,'action' => 'edit')); ?>
	</a>
</p>
<p>
	<a href="<?php echo $router->generate('users_do', array('id' => 27,'action' => 'delete')); ?>">
		GET <?php echo $router->generate('users_do', array('id' => 27,'action' => 'delete')); ?>
	</a>
</p>	
<p>
	<form action="<?php echo $router->generate('users_do', array('id' => 37,'action' => 'submit')); ?>" method="post">
	<button type="submit"> POST <?php echo $router->generate('users_do', array('id' => 37,'action' => 'submit')); ?></button>
	</form>
</p>
<br/><br/><br/>
<?php

// process our matched request

// throw an error if request didnt match
if ($match === false) {
    echo 'Error: no route was matched'; 
    exit;
}

// get our controller and method in $controller and $method vars
list( $controller, $method ) = explode( '#', $match['target'] );

// if controller->action is callable then make the call and pass params
if ( is_callable(array($controller, $method)) ) {
    $obj = new $controller();
    call_user_func_array(array($obj, $method), array($match['params']));

// if controller->action is NOT callable then throw an error    
} else {
    echo 'Error: can not call '.$controller.'->'.$method; 
    exit;
}

?>
