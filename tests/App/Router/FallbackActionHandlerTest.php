<?php

namespace Tests\App\Router;

use LFG\App\Router\ActionHandlerInterface;
use LFG\App\Router\FallbackActionHandler;
use Tests\Fixtures\Controller;

class FallbackActionHandlerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ActionHandlerInterface
     */
    protected $handler;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->handler = new FallbackActionHandler();
    }

    public function testIfReturnTrueWithValidAction()
    {
        $this->assertTrue($this->handler->supportAction(Controller::class.'@main'));
    }

    public function testIfReturnTrueWithInvalidAction()
    {
        $this->assertFalse($this->handler->supportAction('foobar'));
    }

    /**
     * @expectedException \LFG\App\Router\Exception\GivenClassDoNotExistsException
     */
    public function testIfThrowExceptionWithInvalidClass()
    {
        $this->handler->getCallableForAction('', 'foobar@main');
    }

    /**
     * @expectedException \LFG\App\Router\Exception\GivenClassDoNotExistsException
     */
    public function testIfThrowExceptionWithInvalidMethod()
    {
        $this->handler->getCallableForAction('', Controller::class.'@foobar');
    }

    public function testIfReturnCallableWithValidClass()
    {
        $this->assertTrue(is_callable($this->handler->getCallableForAction('', Controller::class.'@main')));
        $this->assertSame('Hello world!', $this->handler->getCallableForAction('', Controller::class.'@main')());
    }
}
