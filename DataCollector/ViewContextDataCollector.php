<?php

namespace Symfony\UX\Steroids\DataCollector;

use Symfony\Bundle\FrameworkBundle\DataCollector\AbstractDataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\UX\Steroids\Templating\View;

class ViewContextDataCollector extends AbstractDataCollector
{
    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
    }

    public function collectViewContext(View $view): void
    {
        $this->data['context_variables'] = $view->getContext();
    }

    public static function getTemplate(): ?string
    {
        return 'data_collector/view_context.html.twig';
    }

    public function getVariables(): array
    {
        return $this->data['context_variables'] ?? [];
    }

    public function getNumberOfVariables(): int
    {
        return \count($this->getVariables());
    }

    public function getVariablesTypes(): array
    {
        return \array_map(static fn($var) => get_debug_type($var), $this->getVariables());
    }

    public function getDumps(): array
    {
        $data = fopen('php://memory', 'r+');

        $cloner = new VarCloner();
        $dumper = new HtmlDumper($data, 'UTF-8');
        $dumps = [];

        foreach ($this->getVariables() as $name => $var) {
            $clone = $cloner->cloneVar($var);
            $dumper->dump($clone);
            $dump = stream_get_contents($data, -1, 0);
            ftruncate($data, 0);
            rewind($data);
            $dumps[$name] = $dump;
        }

        return $dumps;
    }
}
