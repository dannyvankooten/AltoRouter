<?php
/**
 * Performs unit testing for the AltoRouter class.
 *
 * PHP version 5
 *
 * @category AltoRouterTests
 * @package  AltoRouter
 * @author   Danny van Kooten <no@email.given>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/dannyvankooten/AltoRouter
 */
/**
 * Performs unit testing for the AltoRouter class.
 *
 * @category AltoRouterTests
 * @package  AltoRouter
 * @author   Danny van Kooten <no@email.given>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/dannyvankooten/AltoRouter
 */
require 'AltoRouter.php';
/**
 * AltoRouterDebug for our tests.
 *
 * @category AltoRouterDebug
 * @package  AltoRouter
 * @author   Danny van Kooten <no@email.given>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/dannyvankooten/AltoRouter
 */
class AltoRouterDebug extends \AltoRouter\AltoRouter
{
    /**
     * Gets the named routes.
     *
     * @return array
     */
    public function getNamedRoutes()
    {
        return $this->namedRoutes;
    }
    /**
     * Gets the base path.
     *
     * @return array
     */
    public function getBasePath()
    {
        return $this->basePath;
    }
}
/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-07-14 at 17:47:46.
 *
 * @category AltoRouterTest
 * @package  AltoRouter
 * @author   Danny van Kooten <no@email.given>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/dannyvankooten/AltoRouter
 */
class AltoRouterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Stores AltoRouter Object.
     *
     * @var AltoRouter
     */
    protected $router;
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->router = new \AltoRouterDebug;
    }
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @return void
     */
    protected function tearDown()
    {
    }
    /**
     * Tests the getRoutes function.
     *
     * @covers AltoRouter\AltoRouter::getRoutes
     *
     * @return void
     */
    public function testGetRoutes()
    {
        $method = 'POST';
        $route = '/[:controller]/[:action]';
        $target = function () {
        };
        $this->assertInternalType('array', $this->router->getRoutes());
        $this->router->map($method, $route, $target);
        $this->assertEquals(
            array(
                $method => array(
                    array(
                        $route,
                        $target,
                        null
                    )
                )
            ),
            $this->router->getRoutes()
        );
    }
    /**
     * Tests the addRoutes function.
     *
     * @covers AltoRouter\AltoRouter::addRoutes
     *
     * @return void
     */
    public function testAddRoutes()
    {
        $method = 'POST';
        $route = '/[:controller]/[:action]';
        $target = function () {
        };
        $this->router->addRoutes(
            array(
                array($method, $route, $target),
                array($method, $route, $target, 'second_route')
            )
        );
        $routes = $this->router->getRoutes();
        reset($routes);
        foreach (current($routes) as $ind => $r) {
            $this->assertEquals(
                array(
                    $route,
                    $target,
                    $ind ? 'second_route' : null
                ),
                $r
            );
        }
    }
    /**
     * Tests that addRoutes accepts Traversable.
     *
     * @covers AltoRouter\AltoRouter::addRoutes
     *
     * @return void
     */
    public function testAddRoutesAcceptsTraverable()
    {
        if (defined('HHVM_VERSION')) {
            $this->markTestSkipped(
                'HHVM does not support array functions on ArrayObject'
            );
        }

        $traversable = new \ArrayObject(
            array(
                array('GET', '/foo', 'foo_action', null),
                array('POST', '/bar', 'bar_action', 'second_route')
            )
        );
        $first = array(
            array(
                '/foo',
                'foo_action',
                null
            )
        );
        $second = array(
            array(
                '/bar',
                'bar_action',
                'second_route'
            )
        );
        $this->router->addRoutes($traversable);
        $routes = $this->router->getRoutes();
        reset($routes);
        $this->assertEquals($first, current($routes));
        $this->assertEquals($second, next($routes));
    }
    /**
     * Tests it throws exception.
     *
     * @covers AltoRouter\AltoRouter::addRoutes
     *
     * @expectedException Exception
     *
     * @return void
     */
    public function testAddRoutesThrowsExceptionOnInvalidArgument()
    {
        $this->router->addRoutes(new \stdClass);
    }
    /**
     * Tests setting base path.
     *
     * @covers AltoRouter\AltoRouter::setBasePath
     *
     * @return void
     */
    public function testSetBasePath()
    {
        $basePath = $this->router->setBasePath('/some/path');
        $this->assertEquals('/some/path', $this->router->getBasePath());

        $basePath = $this->router->setBasePath('/some/path');
        $this->assertEquals('/some/path', $this->router->getBasePath());
    }
    /**
     * Tests adding default parameters.
     *
     * @covers AltoRouter\AltoRouter::addDefaultParams
     *
     * @return void
     */
    public function testAddDefaultParams()
    {
        $method = 'POST';
        $route = '/[:language]/[:controller]/[:action]';
        $target = function () {
        };
        $name = 'language_route';
        $this->router->addDefaultParams(
            array(
                'language' => 'nl'
            )
        );
        $this->router->map($method, $route, $target, $name);
        $this->assertEquals(
            '/nl/test/foo',
            $this->router->generate(
                'language_route',
                array(
                    'controller' => 'test',
                    'action' => 'foo'
                )
            )
        );
    }
    /**
     * Tests the map method.
     *
     * @covers AltoRouter\AltoRouter::map
     *
     * @return void
     */
    public function testMap()
    {
        $method = 'POST';
        $route = '/[:controller]/[:action]';
        $target = function () {
        };
        $this->router->map($method, $route, $target);
        $routes = $this->router->getRoutes();
        reset($routes);
        $this->assertEquals(
            array(
                array(
                    $route,
                    $target,
                    null
                )
            ),
            current($routes)
        );
    }
    /**
     * Tests the map method with name.
     *
     * @covers AltoRouter\AltoRouter::map
     *
     * @return void
     */
    public function testMapWithName()
    {
        $method = 'POST';
        $route = '/[:controller]/[:action]';
        $target = function () {
        };
        $name = 'myroute';
        $this->router->map($method, $route, $target, $name);
        $routes = $this->router->getRoutes();
        reset($routes);
        $this->assertEquals(
            array(
                array(
                    $route,
                    $target,
                    $name
                )
            ),
            current($routes)
        );
        $named_routes = $this->router->getNamedRoutes();
        $this->assertEquals($route, $named_routes[$name]);
        try{
            $this->router->map($method, $route, $target, $name);
            $this->fail('Should not be able to add existing named route');
        }catch(Exception $e){
            $this->assertEquals(
                "Can not redeclare route '{$name}'", $e->getMessage()
            );
        }
    }
    /**
     * Tests the generate method.
     *
     * @covers AltoRouter\AltoRouter::generate
     *
     * @return void
     */
    public function testGenerate()
    {
        $params = array(
            'controller' => 'test',
            'action' => 'someaction'
        );
        $this->router->map(
            'GET', '/[:controller]/[:action]',
            function () {
            },
            'foo_route'
        );
        $this->assertEquals(
            '/test/someaction',
            $this->router->generate('foo_route', $params)
        );
        $params = array(
            'controller' => 'test',
            'action' => 'someaction',
            'type' => 'json'
        );
        $this->assertEquals(
            '/test/someaction',
            $this->router->generate('foo_route', $params)
        );
    }
    /**
     * Tests the optional urls parts.
     *
     * @return void
     */
    public function testGenerateWithOptionalUrlParts()
    {
        $this->router->map(
            'GET',
            '/[:controller]/[:action].[:type]?',
            function () {
            },
            'bar_route'
        );
        $params = array(
            'controller' => 'test',
            'action' => 'someaction'
        );
        $this->assertEquals(
            '/test/someaction',
            $this->router->generate('bar_route', $params)
        );
        $params = array(
            'controller' => 'test',
            'action' => 'someaction',
            'type' => 'json'
        );
        $this->assertEquals(
            '/test/someaction.json',
            $this->router->generate(
                'bar_route',
                $params
            )
        );
    }
    /**
     * Github #98
     * Test on bare url.
     *
     * @return void
     */
    public function testGenerateWithOptionalOnBareUrl()
    {
        $this->router->map(
            'GET',
            '/[i:page]?',
            function () {
            },
            'bare_route'
        );
        $params = array(
            'page' => 1
        );
        $this->assertEquals(
            '/1',
            $this->router->generate('bare_route', $params)
        );
        $params = array();
        $this->assertEquals(
            '/',
            $this->router->generate('bare_route', $params)
        );
    }
    /**
     * Tests generate witout route.
     *
     * @return void
     */
    public function testGenerateWithNonexistingRoute()
    {
        try{
            $this->router->generate('nonexisting_route');
            $this->fail('Should trigger an exception on nonexisting named route');
        }catch(Exception $e){
            $this->assertEquals(
                "Route 'nonexisting_route' does not exist.", $e->getMessage()
            );
        }
    }
    /**
     * Tests match method and _compileRoute method.
     *
     * @covers AltoRouter\AltoRouter::match
     *
     * @covers AltoRouter\AltoRouter::_compileRoute
     *
     * @return void
     */
    public function testMatch()
    {
        $this->router->map(
            'GET',
            '/foo/[:controller]/[:action]',
            'foo_action',
            'foo_route'
        );
        $this->assertEquals(
            array(
                'target' => 'foo_action',
                'params' => array(
                    'controller' => 'test',
                    'action' => 'do',
                    'method' => 'GET'
                ),
                'name' => 'foo_route'
            ),
            $this->router->match('/foo/test/do', 'GET')
        );
        $this->assertFalse($this->router->match('/foo/test/do', 'POST'));
        $this->assertEquals(
            array(
                'target' => 'foo_action',
                'params' => array(
                    'controller' => 'test',
                    'action' => 'do',
                    'method' => 'GET'
                ),
                'name' => 'foo_route'
            ),
            $this->router->match('/foo/test/do?param=value', 'GET')
        );
    }
    /**
     * Tests match lacking regex.
     *
     * @return void
     */
    public function testMatchWithNonRegex()
    {
        $this->router->map(
            'GET',
            '/about-us',
            'PagesController#about',
            'about_us'
        );
        $this->assertEquals(
            array(
                'target' => 'PagesController#about',
                'params' => array(),
                'name' => 'about_us'
            ),
            $this->router->match('/about-us', 'GET')
        );
        $this->assertFalse($this->router->match('/about-us', 'POST'));
        $this->assertFalse($this->router->match('/about', 'GET'));
        $this->assertFalse($this->router->match('/about-us-again', 'GET'));
    }
    /**
     * Tests match with fixed params.
     *
     * @return void
     */
    public function testMatchWithFixedParamValues()
    {
        $this->router->map(
            'POST',
            '/users/[i:id]/[delete|update:action]',
            'usersController#doAction',
            'users_do'
        );
        $this->assertEquals(
            array(
                'target' => 'usersController#doAction',
                'params' => array(
                    'id' => 1,
                    'action' => 'delete',
                    'method' => 'POST'
                ),
                'name' => 'users_do'
            ),
            $this->router->match('/users/1/delete', 'POST')
        );
        $this->assertFalse($this->router->match('/users/1/delete', 'GET'));
        $this->assertFalse($this->router->match('/users/abc/delete', 'POST'));
        $this->assertFalse($this->router->match('/users/1/create', 'GET'));
    }
    /**
     * Tests match with a plain route.
     *
     * @return void
     */
    public function testMatchWithPlainRoute()
    {
        $router = $this->getMockBuilder('\AltoRouterDebug')
            ->setMethods(array('_compileRoute'))
            ->getMock();
        /**
         * This should prove that _compileRoute is not called when the route doesn't
         * have any params in it, but this doesn't work because _compileRoute
         * is private.
         */
        $router->expects($this->never())
            ->method('_compileRoute');

        $router->map('GET', '/contact', 'website#contact', 'contact');
        // exact match, so no regex compilation necessary
        $this->assertEquals(
            array(
                'target' => 'website#contact',
                'params' => array(
                ),
                'name' => 'contact'
            ),
            $router->match('/contact', 'GET')
        );
        $router->map('GET', '/page/[:id]', 'pages#show', 'page');

        // no prefix match, so no regex compilation necessary
        $this->assertFalse($router->match('/page1', 'GET'));

    }
    /**
     * Test match with request.
     *
     * @return void
     */
    public function testMatchWithRequest()
    {

        $router = $this->getMockBuilder('\AltoRouterDebug')
            ->setMethods(array('getRequestURI', 'getRequestMethod'))
            ->getMock();
        $router->method('getRequestURI')
            ->willReturn('/foo/test/do');
        $router->method('getRequestMethod')
            ->willReturn('POST');
        $router->map(
            'POST',
            '/foo/[:controller]/[:action]',
            'foo_action',
            'foo_route'
        );
        $this->assertEquals(
            array(
                'target' => 'foo_action',
                'params' => array(
                    'controller' => 'test',
                    'action' => 'do',
                    'method' => 'POST'
                ),
                'name' => 'foo_route'
            ),
            $router->match()
        );
    }
    /**
     * Tests match with passed in url parts.
     *
     * @return void
     */
    public function testMatchWithOptionalUrlParts()
    {
        $this->router->map(
            'GET',
            '/bar/[:controller]/[:action].[:type]?',
            'bar_action',
            'bar_route'
        );
        $this->assertEquals(
            array(
                'target' => 'bar_action',
                'params' => array(
                    'controller' => 'test',
                    'action' => 'do',
                    'type' => 'json',
                    'method' => 'GET'
                ),
                'name' => 'bar_route'
            ),
            $this->router->match('/bar/test/do.json', 'GET')
        );
        $this->assertEquals(
            array(
                'target' => 'bar_action',
                'params' => array(
                    'controller' => 'test',
                    'action' => 'do',
                    'method' => 'GET'
                ),
                'name' => 'bar_route'
            ),
            $this->router->match('/bar/test/do', 'GET')
        );
    }
    /**
     * Github #98
     * Test match on bare url.
     *
     * @return void
     */
    public function testMatchWithOptionalPartOnBareUrl()
    {
        $this->router->map(
            'GET',
            '/[i:page]?',
            'bare_action',
            'bare_route'
        );
        $this->assertEquals(
            array(
                'target' => 'bare_action',
                'params' => array(
                    'page' => 1,
                    'method' => 'GET'
                ),
                'name' => 'bare_route'
            ),
            $this->router->match('/1', 'GET')
        );
        $this->assertEquals(
            array(
                'target' => 'bare_action',
                'params' => array(
                    'method' => 'GET'
                ),
                'name' => 'bare_route'
            ),
            $this->router->match('/', 'GET')
        );
    }
    /**
     * Tests match with wildcard
     *
     * @return void
     */
    public function testMatchWithWildcard()
    {
        $this->router->map('GET', '/a', 'foo_action', 'foo_route');
        $this->router->map('GET', '*', 'bar_action', 'bar_route');
        $this->assertEquals(
            array(
                'target' => 'bar_action',
                'params' => array(),
                'name' => 'bar_route'
            ),
            $this->router->match('/everything', 'GET')
        );

    }
    /**
     * Test custom regex
     *
     * @return void
     */
    public function testMatchWithCustomRegexp()
    {
        $this->router->map(
            'GET',
            '@^/[a-z]*$',
            'bar_action',
            'bar_route'
        );
        $this->assertEquals(
            array(
                'target' => 'bar_action',
                'params' => array(
                    'method' => 'GET'
                ),
                'name' => 'bar_route'
            ),
            $this->router->match('/everything', 'GET')
        );
        $this->assertFalse($this->router->match('/some-other-thing', 'GET'));
    }
    /**
     * Test unicode regex
     *
     * @return void
     */
    public function testMatchWithUnicodeRegex()
    {
        $pattern = '/(?<path>[^';
        // Arabic characters
        $pattern .= '\x{0600}-\x{06FF}';
        $pattern .= '\x{FB50}-\x{FDFD}';
        $pattern .= '\x{FE70}-\x{FEFF}';
        $pattern .= '\x{0750}-\x{077F}';
        // Alphanumeric, /, _, - and space characters
        $pattern .= 'a-zA-Z0-9\/_\-\s';
        // 'ZERO WIDTH NON-JOINER'
        $pattern .= '\x{200C}';
        $pattern .= ']+)';
        $this->router->map('GET', '@' . $pattern, 'unicode_action', 'unicode_route');
        $this->assertEquals(
            array(
                'target' => 'unicode_action',
                'name' => 'unicode_route',
                'params' => array(
                    'path' => '大家好',
                    'method' => 'GET'
                )
            ),
            $this->router->match('/大家好', 'GET')
        );
        $this->assertFalse($this->router->match('/﷽‎', 'GET'));
    }
    /**
     * Tests add match types.
     *
     * @covers AltoRouter\AltoRouter::addMatchTypes
     *
     * @return void
     */
    public function testMatchWithCustomNamedRegex()
    {
        $this->router->addMatchTypes(
            array(
                'cId' => '[a-zA-Z]{2}[0-9](?:_[0-9]++)?'
            )
        );
        $this->router->map(
            'GET',
            '/bar/[cId:customId]',
            'bar_action',
            'bar_route'
        );
        $this->assertEquals(
            array(
                'target' => 'bar_action',
                'params' => array(
                    'customId' => 'AB1',
                    'method' => 'GET'
                ),
                'name' => 'bar_route'
            ),
            $this->router->match('/bar/AB1', 'GET')
        );
        $this->assertEquals(
            array(
                'target' => 'bar_action',
                'params' => array(
                    'customId' => 'AB1_0123456789',
                    'method' => 'GET'
                ),
                'name' => 'bar_route'
            ),
            $this->router->match('/bar/AB1_0123456789', 'GET')
        );
        $this->assertFalse($this->router->match('/some-other-thing', 'GET'));
    }
    /**
     * Test custom with unicode.
     *
     * @return void
     */
    public function testMatchWithCustomNamedUnicodeRegex()
    {
        $pattern = '[^';
        // Arabic characters
        $pattern .= '\x{0600}-\x{06FF}';
        $pattern .= '\x{FB50}-\x{FDFD}';
        $pattern .= '\x{FE70}-\x{FEFF}';
        $pattern .= '\x{0750}-\x{077F}';
        $pattern .= ']+';
        $this->router->addMatchTypes(array('nonArabic' => $pattern));
        $this->router->map(
            'GET',
            '/bar/[nonArabic:string]',
            'non_arabic_action',
            'non_arabic_route'
        );
        $this->assertEquals(
            array(
                'target' => 'non_arabic_action',
                'name' => 'non_arabic_route',
                'params' => array(
                    'string' => 'some-path',
                    'method' => 'GET'
                )
            ),
            $this->router->match('/bar/some-path', 'GET')
        );
        $this->assertFalse($this->router->match('/﷽‎', 'GET'));
    }
}
