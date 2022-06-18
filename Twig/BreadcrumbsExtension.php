<?php

namespace Symfony\UX\Steroids\Twig;

use Symfony\UX\Steroids\Breadcrumbs\Tree;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFunction;

class BreadcrumbsExtension extends AbstractExtension implements GlobalsInterface
{
    private Tree $tree;

    public function __construct()
    {
        $this->tree = new Tree();
    }

    public function getGlobals(): array
    {
        return [
            'breadcrumbs' => $this->tree,
        ];
    }

    public function getFunctions(): iterable
    {
        yield new TwigFunction('breadcrumbs_render', [$this, 'render'], [
            'needs_environment' => true,
            'is_safe' => ['html'],
        ]);
    }

    public function render(Environment $environment, string $template): string
    {
        return $environment->render($template, [
            'tree' => $this->tree,
        ]);
    }
}
