<?php

namespace Symfony\UX\Steroids\Breadcrumbs;

use ArrayIterator;
use IteratorAggregate;
use Traversable;

class Tree implements IteratorAggregate
{
    private array $items;

    public function __construct()
    {
        $this->items = [];
    }

    public function root(string $label, ?string $url): void
    {
        $this->items = [];
        $this->items[] = new Item($label, $url);
    }

    public function addLink(string $label, string $url): void
    {
        $this->items[] = Item::link($label, $url);
    }

    public function addText(string $label): void
    {
        $this->items[] = Item::text($label);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }
}
