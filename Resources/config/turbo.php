<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\UX\Steroids\EventListener\PreferredResponseSubscriber;
use Symfony\UX\Steroids\EventListener\TurboModalSubscriber;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set('ux.turbo.modal.listener', TurboModalSubscriber::class)
            ->tag('kernel.event_subscriber')

        ->set('ux.turbo.preferred_response.listener', PreferredResponseSubscriber::class)
            ->args([
                service('twig'),
            ])
            ->tag('kernel.event_subscriber')
    ;
};
