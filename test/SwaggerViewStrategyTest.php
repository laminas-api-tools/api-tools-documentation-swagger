<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-swagger for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\ApiTools\Documentation\Swagger;

use Laminas\ApiTools\Documentation\Swagger\SwaggerViewStrategy;
use Laminas\ApiTools\Documentation\Swagger\ViewModel;
use Laminas\EventManager\EventManager;
use Laminas\Http\Response as HttpResponse;
use Laminas\Stdlib\Response as StdlibResponse;
use Laminas\View\Renderer\JsonRenderer;
use Laminas\View\ViewEvent;
use PHPUnit_Framework_TestCase as TestCase;

class SwaggerViewStrategyTest extends TestCase
{
    public function setUp()
    {
        $this->events   = new EventManager();
        $this->renderer = new JsonRenderer();
        $this->strategy = new SwaggerViewStrategy($this->renderer);
        $this->strategy->attach($this->events);
    }

    public function testStrategyAttachesToViewEventsAtPriority200()
    {
        $listeners = $this->events->getListeners(ViewEvent::EVENT_RENDERER);
        $this->assertEquals(1, count($listeners));
        $listener = $listeners->top();
        $this->assertEquals(array($this->strategy, 'selectRenderer'), $listener->getCallback());
        $this->assertEquals(200, $listener->getMetadatum('priority'));

        $listeners = $this->events->getListeners(ViewEvent::EVENT_RESPONSE);
        $this->assertEquals(1, count($listeners));
        $listener = $listeners->top();
        $this->assertEquals(array($this->strategy, 'injectResponse'), $listener->getCallback());
        $this->assertEquals(200, $listener->getMetadatum('priority'));
    }

    public function testSelectRendererReturnsJsonRendererWhenSwaggerViewModelIsPresentInEvent()
    {
        $event = new ViewEvent();
        $event->setName(ViewEvent::EVENT_RENDERER);
        $event->setModel(new ViewModel(array()));

        $renderer = $this->strategy->selectRenderer($event);
        $this->assertSame($this->renderer, $renderer);
        return $event;
    }

    public function testSelectRendererReturnsNullWhenSwaggerViewModelIsNotPresentInEvent()
    {
        $event = new ViewEvent();
        $event->setName(ViewEvent::EVENT_RENDERER);

        $this->assertNull($this->strategy->selectRenderer($event));
        return $event;
    }

    /**
     * @depends testSelectRendererReturnsJsonRendererWhenSwaggerViewModelIsPresentInEvent
     */
    public function testInjectResponseSetsContentTypeWhenJsonRendererWasSelectedBySelectRendererEvent($event)
    {
        $response = new HttpResponse();
        $event->setResponse($response);
        $this->strategy->selectRenderer($event);
        $this->strategy->injectResponse($event);
        $headers = $response->getHeaders();
        $this->assertTrue($headers->has('Content-Type'), 'No Content-Type header in HTTP response!');
        $header = $headers->get('Content-Type');
        $this->assertContains('application/vnd.swagger+json', $header->getFieldValue());
    }

    /**
     * @depends testSelectRendererReturnsNullWhenSwaggerViewModelIsNotPresentInEvent
     */
    public function testInjectResponseDoesNothingWhenJsonRendererWasNotSelectedBySelectRendererEvent($event)
    {
        $response = new HttpResponse();
        $event->setResponse($response);
        $this->strategy->selectRenderer($event);
        $this->strategy->injectResponse($event);
        $headers = $response->getHeaders();
        $this->assertFalse($headers->has('Content-Type'), 'No Content-Type header in HTTP response!');
    }

    /**
     * @depends testSelectRendererReturnsJsonRendererWhenSwaggerViewModelIsPresentInEvent
     */
    public function testInjectResponseDoesNothingIfResponseIsNotHttpEnabled($event)
    {
        $response = new StdlibResponse();
        $event->setResponse($response);
        $this->strategy->selectRenderer($event);
        $this->strategy->injectResponse($event);
        $this->assertFalse(method_exists($response, 'getHeaders'));
    }
}
