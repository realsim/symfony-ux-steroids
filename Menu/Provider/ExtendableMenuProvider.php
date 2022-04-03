<?php

namespace Symfony\UX\Steroids\Menu\Provider;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\Provider\MenuProviderInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\UX\Steroids\Menu\Event\ExtendMenuEvent;

final class ExtendableMenuProvider implements MenuProviderInterface
{
    private FactoryInterface $factory;
    private EventDispatcherInterface $dispatcher;

    public function __construct(FactoryInterface $factory, EventDispatcherInterface $dispatcher)
    {
        $this->factory = $factory;
        $this->dispatcher = $dispatcher;
    }

    public function get(string $name, array $options = []): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $event = new ExtendMenuEvent($menu, $name);
        $this->dispatcher->dispatch($event);

        return $menu;
    }

    public function has(string $name, array $options = []): bool
    {
        return true;
    }
}
