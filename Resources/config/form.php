<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Symfony\UX\Steroids\Form\Extension\AsyncChoiceCreationExtension;
use Symfony\UX\Steroids\Form\Extension\DefaultDataExtension;
use Symfony\UX\Steroids\Form\Extension\OperatorModeExtension;
use Symfony\UX\Steroids\Form\Extension\TurboModalConfigPassExtension;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set('ux.form.type_extension.async_choice_creation', AsyncChoiceCreationExtension::class)
            ->args([
                service('router')
            ])
            ->tag('form.type_extension')

        ->set('ux.form.type_extension.default_data', DefaultDataExtension::class)
            ->tag('form.type_extension')

        ->set('ux.form.type_extension.operator_mode', OperatorModeExtension::class)
            ->tag('form.type_extension')

        ->set('ux.form.type_extension.turbo_modal_config', TurboModalConfigPassExtension::class)
            ->args([
                service('request_stack')
            ])
            ->tag('form.type_extension')

    ;
};
