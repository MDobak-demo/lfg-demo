<?php

class RouteTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Route
     */
    protected $router;

    public static function setUpBeforeClass()
    {
        Route::bind_action('/p1', 'action_p1');
        Route::bind_action('/p2', 'action_p2');
        Route::bind_action('/p2/completed', 'action_p2_completed');
        Route::bind_action('/p2/sorry', 'action_p2_sorry');
        Route::bind_action('/p2/(((((.)))))*', 'action_p2_regexp');
        Route::bind_function('/p3', function() { return 'p3'; });
        Route::bind_action('/p4/a', 'action_p4_shorter');
        Route::bind_action('/p4/a/b', 'action_p4_longer');
    }

    public function testFindValidRoute()
    {
        $result = Route::find_route('p1');

        $this->assertSame('p1', $result['route']);
        $this->assertSame('action_p1', $result['action']);
        $this->assertSame('', $result['query']);
        $this->assertTrue(is_callable($result['callback']));
        $this->assertSame('action_p1', $result['callback']());
    }

    public function testFindRouteWithRegexp()
    {
        $result = Route::find_route('p2/something');

        // Regexp should't work
        $this->assertNotSame('action_p2_regexp', $result['action']);
    }

    public function testFindValidRouteWithCallable()
    {
        $result = Route::find_route('p3');

        $this->assertSame('p3', $result['route']);
        $this->assertSame('', $result['action']);
        $this->assertSame('', $result['query']);
        $this->assertTrue(is_callable($result['callback']));
        $this->assertSame('p3', $result['callback']());
    }

    public function testFindInvalid()
    {
        $result = Route::find_route('dist_route_do_not_exists');

        $this->assertNull($result);
    }

    public function testFindActionUrl()
    {
        $result = Route::find_action_url('action_p1');

        $this->assertSame('p1', $result);
    }

    public function testFindingMoreSpecifiedRoutes()
    {
        $result = Route::find_route('p4/a/b');

        $this->assertSame('action_p4_longer', $result['action']);
    }

    public function testFailOnRouteWithLeadingSlash()
    {
        $result = Route::find_route('/p1');

        $this->assertNull($result);
    }
}
