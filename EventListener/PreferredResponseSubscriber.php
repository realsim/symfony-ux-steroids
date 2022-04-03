<?php

namespace Symfony\UX\Steroids\EventListener;

use Symfony\UX\Steroids\Templating\View;
use Symfony\UX\Steroids\DataCollector\ViewContextDataCollector;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class PreferredResponseSubscriber implements EventSubscriberInterface
{
    private Environment $renderer;
    private ?ViewContextDataCollector $collector;

    public function __construct(Environment $renderer, ?ViewContextDataCollector $collector = null)
    {
        $this->renderer = $renderer;
        $this->collector = $collector;
    }

    public function getPreferredResponse(ViewEvent $event): void
    {
        $request = $event->getRequest();
        $view = $event->getControllerResult();

        if (!$view instanceof View) {
            return;
        }

        $response = $view->getResponse($request, $this->renderer);
        $event->setResponse($response);
    }

    public function collectViewContext(ViewEvent $event): void
    {
        if (null === $this->collector) {
            return;
        }

        $view = $event->getControllerResult();

        if (!$view instanceof View) {
            return;
        }

        $this->collector->collectViewContext($view);
    }

    public static function getSubscribedEvents(): iterable
    {
        yield KernelEvents::VIEW => [
            ['collectViewContext', 50],
            ['getPreferredResponse', 0],
        ];
    }
}
