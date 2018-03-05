<?php

namespace Tests\App\Router;

use LFG\App\Router\Router;

class RouterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Router
     */
    protected $router;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->router = new Router();

        $this->router->bindAction('/p1', 'action_p1');
        $this->router->bindAction('/p2', 'action_p2');
        $this->router->bindAction('/p2/completed', 'action_p2_completed');
        $this->router->bindAction('/p2/sorry', 'action_p2_sorry');
        $this->router->bindAction('/p2/(((((.)))))*', 'action_p2_regexp');
        $this->router->bindFunction('/p3', function() { return 'p3'; });
        $this->router->bindAction('/p4/a', 'action_p4_shorter');
        $this->router->bindAction('/p4/a/b', 'action_p4_longer');
    }

    public function testFindValidRoute()
    {
        $result = $this->router->findRoute('p1');

        $this->assertSame('p1', $result['route']);
        $this->assertSame('action_p1', $result['action']);
        $this->assertSame('', $result['query']);
        $this->assertTrue(is_callable($result['callback']));
        $this->assertSame('action_p1', $result['callback']());
    }

    public function testFindRouteWithRegexp()
    {
        $result = $this->router->findRoute('p2/something');

        // Regexp should't work
        $this->assertNotSame('action_p2_regexp', $result['action']);
    }

    public function testFindValidRouteWithCallable()
    {
        $result = $this->router->findRoute('p3');

        $this->assertSame('p3', $result['route']);
        $this->assertSame('', $result['action']);
        $this->assertSame('', $result['query']);
        $this->assertTrue(is_callable($result['callback']));
        $this->assertSame('p3', $result['callback']());
    }

    public function testFindInvalid()
    {
        $result = $this->router->findRoute('dist_route_do_not_exists');

        $this->assertNull($result);
    }

    public function testFindActionUrl()
    {
        $result = $this->router->findActionUrl('action_p1');

        $this->assertSame('p1', $result);
    }

    public function testFindingMoreSpecifiedRoutes()
    {
        $result = $this->router->findRoute('p4/a/b');

        $this->assertSame('action_p4_longer', $result['action']);
    }

    public function testFailOnRouteWithLeadingSlash()
    {
        $result = $this->router->findRoute('/p1');

        $this->assertNull($result);
    }
}
