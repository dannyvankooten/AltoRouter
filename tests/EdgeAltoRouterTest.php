<?php

require 'EdgeAltoRouter.php';

class EdgeAltoRouterDebug extends EdgeAltoRouter
{
    public function getNamedRoutes()
    {
        return $this->namedRoutes;
    }

    public function getBasePath()
    {
        return $this->basePath;
    }
}

class EdgeAltoRouterTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var EdgeAltoRouter
     */
    protected $router;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() : void
    {
        $this->router = new EdgeAltoRouterDebug;
    }

    /**
     * @covers EdgeAltoRouter::setRouteFromConfig
     */
    public function testSetRouteFromConfig()
    {
        $configModelUrl = __DIR__.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'route.model';

        $this->router->setRouteFromConfig($configModelUrl,self::NEED_SPEED);
        
        $routes = $this->router->getRoutes();

        /*
        print_r($routes);
        print_r($this->router->match('/home#index', 'GET'));
        print_r($this->router->match('/users/1', 'GET'));
        */

        $method = 'GET';
        $route = '/users/';
        $target = array('c' => 'UserController','a' => 'ListAction')
        ;
        $this->assertEquals([$method, $route, $target, ''], $routes[1]);

        $this->assertEquals([
            'target' => 'home#index',
            'params' => [],
            'name' => 'home'
        ], $this->router->match('/', 'GET'));

        $this->assertEquals([
            'target' => 'home#index',
            'params' => [],
            'name' => 'home'
        ], $this->router->match('/', 'POST'));

        $this->assertEquals([
            'target' => ['c' => 'UserController','a' => 'ListAction'],
            'params' => [],
            'name' => ''
        ], $this->router->match('/users/', 'GET'));


        $this->assertEquals([
            'target' => 'users#show',
            'params' => [
                'id' => 1
            ],
            'name' => 'users_show'
        ], $this->router->match('/users/1', 'GET'));

        $this->assertEquals([
            'target' => 'usersController#doAction',
            'params' => [
                'id' => 1,
                'action' => 'delete'
            ],
            'name' => 'users_do'
        ], $this->router->match('/users/1/delete', 'POST'));

    }
}
