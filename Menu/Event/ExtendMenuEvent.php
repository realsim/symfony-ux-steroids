<?php

namespace Symfony\UX\Steroids\Menu\Event;

use Knp\Menu\ItemInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class ExtendMenuEvent extends Event
{
    private ItemInterface $menu;
    private string $name;

    public function __construct(ItemInterface $menu, string $name)
    {
        $this->menu = $menu;
        $this->name = $name;
    }

    public function getMenu(): ItemInterface
    {
        return $this->menu;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
