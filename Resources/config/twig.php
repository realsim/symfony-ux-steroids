<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Mechanic\Twig\Extra\Heroicons\HeroiconsExtension;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set('twig.extension.heroicons', HeroiconsExtension::class)
            ->tag('twig.extension')

    ;
};
