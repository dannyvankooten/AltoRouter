<?php

require 'AltoRouter.php';
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

class SimpleTraversable implements Iterator
{

    protected $_position = 0;

    protected $_data = [
        ['GET', '/foo', 'foo_action', null],
        ['POST', '/bar', 'bar_action', 'second_route']
    ];

    #[\ReturnTypeWillChange]
    public function current()
    {
        return $this->_data[$this->_position];
    }

    #[\ReturnTypeWillChange]
    public function key()
    {
        return $this->_position;
    }
    public function next() : void
    {
        ++$this->_position;
    }
    public function rewind() : void
    {
        $this->_position = 0;
    }
    public function valid() : bool
    {
        return isset($this->_data[$this->_position]);
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
        $configModelUrl = __DIR__.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'route.config';

        $this->router->setRouteFromConfig($configModelUrl);
        
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
