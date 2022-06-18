<?php

namespace Symfony\UX\Steroids\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\UX\Steroids\Menu\MenuExtensionInterface;
use Symfony\UX\Steroids\Twig\Component\LiveFormComponent;

class UXSteroidsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('form.php');
        $loader->load('menu.php');
        $loader->load('twig.php');
        $loader->load('turbo.php');

        $container->registerForAutoconfiguration(MenuExtensionInterface::class)
            ->addTag('ux.menu.extension');
    }
}
