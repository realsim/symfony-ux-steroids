<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\UX\Steroids\EventListener\MenuExtensionSubscriber;
use Symfony\UX\Steroids\Menu\Provider\ExtendableMenuProvider;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set('ux.menu.extendable_menu_provider', ExtendableMenuProvider::class)
            ->args([
                service('knp_menu.factory'),
                service('event_dispatcher'),
            ])
            ->tag('knp_menu.provider')

        ->set('ux.menu.menu_extension_subscriber', MenuExtensionSubscriber::class)
            ->args([
                tagged_iterator('ux.menu.extension')
            ])
            ->tag('kernel.event_subscriber')

    ;
};
