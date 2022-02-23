<?php

declare(strict_types=1);

namespace LaminasTest\ApiTools\Documentation\Swagger;

use Laminas\ApiTools\Documentation\Swagger\SwaggerViewStrategy;
use Laminas\ApiTools\Documentation\Swagger\ViewModel;
use Laminas\EventManager\EventManager;
use Laminas\EventManager\Test\EventListenerIntrospectionTrait;
use Laminas\Http\Response as HttpResponse;
use Laminas\Stdlib\Response as StdlibResponse;
use Laminas\View\Renderer\JsonRenderer;
use Laminas\View\ViewEvent;
use PHPUnit\Framework\TestCase;

use function method_exists;

class SwaggerViewStrategyTest extends TestCase
{
    use EventListenerIntrospectionTrait;

    protected function setUp(): void
    {
        $this->events   = new EventManager();
        $this->renderer = new JsonRenderer();
        $this->strategy = new SwaggerViewStrategy($this->renderer);
        $this->strategy->attach($this->events);
    }

    public function testStrategyAttachesToViewEventsAtPriority200(): void
    {
        $this->assertListenerAtPriority(
            [$this->strategy, 'selectRenderer'],
            200,
            ViewEvent::EVENT_RENDERER,
            $this->events
        );

        $this->assertListenerAtPriority(
            [$this->strategy, 'injectResponse'],
            200,
            ViewEvent::EVENT_RESPONSE,
            $this->events
        );
    }

    public function testSelectRendererReturnsJsonRendererWhenSwaggerViewModelIsPresentInEvent(): ViewEvent
    {
        $event = new ViewEvent();
        $event->setName(ViewEvent::EVENT_RENDERER);
        $event->setModel(new ViewModel([]));

        $renderer = $this->strategy->selectRenderer($event);
        $this->assertSame($this->renderer, $renderer);
        return $event;
    }

    public function testSelectRendererReturnsNullWhenSwaggerViewModelIsNotPresentInEvent(): ViewEvent
    {
        $event = new ViewEvent();
        $event->setName(ViewEvent::EVENT_RENDERER);

        $this->assertNull($this->strategy->selectRenderer($event));
        return $event;
    }

    /**
     * @depends testSelectRendererReturnsJsonRendererWhenSwaggerViewModelIsPresentInEvent
     */
    public function testInjectResponseSetsContentTypeWhenJsonRendererWasSelectedBySelectRendererEvent(
        ViewEvent $event
    ): void {
        $response = new HttpResponse();
        $event->setResponse($response);
        $this->strategy->selectRenderer($event);
        $this->strategy->injectResponse($event);
        $headers = $response->getHeaders();
        $this->assertTrue($headers->has('Content-Type'), 'No Content-Type header in HTTP response!');
        $header = $headers->get('Content-Type');
        $this->assertStringContainsString('application/vnd.swagger+json', $header->getFieldValue());
    }

    /**
     * @depends testSelectRendererReturnsNullWhenSwaggerViewModelIsNotPresentInEvent
     */
    public function testInjectResponseDoesNothingWhenJsonRendererWasNotSelectedBySelectRendererEvent(
        ViewEvent $event
    ): void {
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
    public function testInjectResponseDoesNothingIfResponseIsNotHttpEnabled(ViewEvent $event): void
    {
        $response = new StdlibResponse();
        $event->setResponse($response);
        $this->strategy->selectRenderer($event);
        $this->strategy->injectResponse($event);
        $this->assertFalse(method_exists($response, 'getHeaders'));
    }
}
