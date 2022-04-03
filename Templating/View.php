<?php

namespace Symfony\UX\Steroids\Templating;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\Turbo\Stream\TurboStreamResponse;
use Twig\Environment;
use LogicException;

class View
{
    private const HTML = 'html';
    private const TURBO_STREAM = 'turbo_stream';

    private array $views;
    private array $context;
    private ?RedirectResponse $redirectResponse;

    public static function withContext(array $context = []): self
    {
        return new static($context);
    }

    protected function __construct(array $context)
    {
        $this->views = [];
        $this->context = $context;
        $this->redirectResponse = null;
    }

    public function html(string $view, Response $response = null): self
    {
        $this->views[self::HTML] = [$view, $response];

        return $this;
    }

    public function turboStream(string $view, Response $response = null): self
    {
        if (null === $response) {
            $response = new TurboStreamResponse(headers: [
                'Content-Type' => TurboStreamResponse::STREAM_MEDIA_TYPE,
            ]);
        }

        $this->views[self::TURBO_STREAM] = [$view, $response];

        return $this;
    }

    public function otherwiseRedirect(RedirectResponse $response): self
    {
        $this->redirectResponse = $response;

        return $this;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function getResponse(Request $request, Environment $renderer): Response
    {
        $preferredFormat = $this->getPreferredResponseFormat($request);
        $formatsPriority = [
            self::TURBO_STREAM,
            self::HTML,
        ];

        foreach ($formatsPriority as $format) {
            if (!($format === $preferredFormat && isset($this->views[$format]))) {
                continue;
            }

            [$view, $response] = $this->views[$format];

            return $this->renderView($renderer, $view, $response);
        }

        if (null !== $this->redirectResponse) {
            return $this->redirectResponse;
        }

        throw new LogicException('At least one of response formats or fallback redirect response must be set.');
    }

    private function getPreferredResponseFormat(Request $request): string
    {
        if (TurboStreamResponse::STREAM_FORMAT === $request->getPreferredFormat() || $request->headers->has('Turbo-Frame')) {
            return self::TURBO_STREAM;
        }

        return $request->getPreferredFormat(self::HTML);
    }

    private function renderView(Environment $renderer, string $view, Response $response = null): Response
    {
        $content = $renderer->render($view, $this->context);

        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($content);

        return $response;
    }
}
