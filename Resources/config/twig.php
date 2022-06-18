<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Mechanic\Twig\Extra\Heroicons\HeroiconsExtension;
use Symfony\UX\Steroids\Twig\BreadcrumbsExtension;
use Symfony\UX\Steroids\Twig\DomExtension;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set('twig.extension.heroicons', HeroiconsExtension::class)
            ->tag('twig.extension')

        ->set('twig.extension.dom_helper', DomExtension::class)
            ->args([
                service('property_accessor'),
                service('request_stack'),
                service('url_helper'),
            ])
            ->tag('twig.extension')

        ->set('twig.extension.breadcrumbs', BreadcrumbsExtension::class)
            ->tag('twig.extension')
    ;
};
