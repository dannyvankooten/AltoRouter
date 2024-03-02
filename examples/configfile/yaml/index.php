<?php

require __DIR__ . '/../../EdgeAltoRouter.php';

/**
 * This can be useful if you're using PHP's built-in web server, to serve files like images or css
 * @link https://secure.php.net/manual/en/features.commandline.webserver.php
 */
if (file_exists($_SERVER['SCRIPT_FILENAME']) && pathinfo($_SERVER['SCRIPT_FILENAME'], PATHINFO_EXTENSION) !== 'php') {
    return;
}

$router = new EdgeAltoRouter(configModelUrl: __DIR__.DIRECTORY_SEPARATOR."routes.yml");
$router->setBasePath('/EdgeAltoRouter/examples/basic');

// match current request
$match = $router->match();
?>
<h1>AltoRouter</h1>

<h3>Current request: </h3>
<pre>
    Target: <?php var_dump($match['target']); ?>
    Params: <?php var_dump($match['params']); ?>
    Name:   <?php var_dump($match['name']); ?>
</pre>

<h3>Try these requests: </h3>
<p><a href="<?php echo $router->generate('home'); ?>">GET <?php echo $router->generate('home'); ?></a></p>
<p><a href="<?php echo $router->generate('users_show', ['id' => 5]); ?>">GET <?php echo $router->generate('users_show', ['id' => 5]); ?></a></p>
<p><form action="<?php echo $router->generate('users_do', ['id' => 10, 'action' => 'update']); ?>" method="post"><button type="submit"><?php echo $router->generate('users_do', ['id' => 10, 'action' => 'update']); ?></button></form></p>
