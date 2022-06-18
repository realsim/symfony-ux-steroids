<?php

namespace Symfony\UX\Steroids\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\Steroids\Modal\TurboModal;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\UX\Turbo\Stream\TurboStreamResponse;

class TurboModalSubscriber implements EventSubscriberInterface
{
    public function onRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (null !== $modalId = $request->headers->get('Turbo-Modal-ID')) {
            $request->attributes->set('turbo_modal', new TurboModal($modalId, $request->headers->get('Turbo-Modal-Target-Frame')));
        }
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        if ($this->isTurboModalRedirect($event->getRequest(), $response)) {
            $event->setResponse(new Response(null, Response::HTTP_OK, [
                'Turbo-Modal-Location' => $response->headers->get('Location'),
            ]));
            return;
        }

        if (!($response instanceof TurboStreamResponse && $response->getStatusCode() === TurboStreamResponse::HTTP_OK)) {
            return;
        }

        $request = $event->getRequest();
        $turboModal = $request->attributes->get('turbo_modal');

        if ($turboModal instanceof TurboModal) {
            $this->addModalDismiss($response, $turboModal->id);
        }
    }

    private function isTurboModalRedirect(Request $request, Response $response): bool
    {
        return $response->isRedirection()
            && $request->headers->has('Turbo-Frame')
            && $request->attributes->has('turbo_modal');
    }

    private function addModalDismiss(TurboStreamResponse $response, string $modalId): void
    {
        $content = $response->getContent();
        $content .= <<<HTML
<turbo-stream action="remove" target="$modalId"></turbo-stream>
HTML;

        $response->setContent($content);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onRequest',
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }
}
