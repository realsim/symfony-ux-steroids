<?php

namespace Symfony\UX\Steroids\Menu;

use Knp\Menu\ItemInterface;

interface MenuExtensionInterface
{
    public function getExtendedMenus(): iterable;

    public function extend(ItemInterface $menu): void;
}
