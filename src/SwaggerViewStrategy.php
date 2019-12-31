<?php

/**
 * @see       https://github.com/laminas-api-tools/api-tools-documentation-swagger for the canonical source repository
 * @copyright https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas-api-tools/api-tools-documentation-swagger/blob/master/LICENSE.md New BSD License
 */

namespace Laminas\ApiTools\Documentation\Swagger;

use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\EventManager\ListenerAggregateTrait;
use Laminas\View\Renderer\JsonRenderer;
use Laminas\View\ViewEvent;

class SwaggerViewStrategy implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * @var ViewModel
     */
    protected $model;

    /**
     * @var JsonRenderer
     */
    protected $renderer;

    /**
     * @param JsonRenderer $renderer
     */
    public function __construct(JsonRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events, $priority = 200)
    {
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RENDERER, [$this, 'selectRenderer'], $priority);
        $this->listeners[] = $events->attach(ViewEvent::EVENT_RESPONSE, [$this, 'injectResponse'], $priority);
    }

    /**
     * @param ViewEvent $e
     * @return null|JsonRenderer
     */
    public function selectRenderer(ViewEvent $e)
    {
        $model = $e->getModel();
        if (! $model instanceof ViewModel) {
            return;
        }

        $this->model = $model;
        return $this->renderer;
    }

    /**
     * @param ViewEvent $e
     */
    public function injectResponse(ViewEvent $e)
    {
        if (! $this->model instanceof ViewModel) {
            return;
        }

        $response = $e->getResponse();
        if (! method_exists($response, 'getHeaders')) {
            return;
        }

        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Type', 'application/vnd.swagger+json');
    }
}
