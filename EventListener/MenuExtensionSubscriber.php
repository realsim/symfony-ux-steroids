<?php

namespace Symfony\UX\Steroids\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\UX\Steroids\Menu\Event\ExtendMenuEvent;
use Symfony\UX\Steroids\Menu\MenuExtensionInterface;

class MenuExtensionSubscriber implements EventSubscriberInterface
{
    private array $extensions = [];

    public function __construct(iterable $extensions)
    {
        foreach ($extensions as $extension) {
            $this->addExtension($extension);
        }
    }

    public function addExtension(MenuExtensionInterface $extension): void
    {
        foreach ($extension->getExtendedMenus() as $extendedMenu) {
            $this->extensions[$extendedMenu][] = $extension;
        }
    }

    public function getMenuExtensions(string $extendedMenu): iterable
    {
        return $this->extensions[$extendedMenu] ?? [];
    }

    public function onExtend(ExtendMenuEvent $event): void
    {
        $menu = $event->getMenu();

        foreach ($this->getMenuExtensions($event->getName()) as $extension) {
            $extension->extend($menu);
        }
    }

    public static function getSubscribedEvents(): iterable
    {
        yield ExtendMenuEvent::class => 'onExtend';
    }
}
