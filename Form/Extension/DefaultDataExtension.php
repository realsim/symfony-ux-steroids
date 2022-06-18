<?php

namespace Symfony\UX\Steroids\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DefaultDataExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $defaultData = $options['default_data'] ?: null;

        if (null !== $defaultData) {
            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($defaultData) {
                // Если в форму не переданы доменные данные (model data), устанавливается указанное дефолтное значение
                // У checkbox и radio, вместо null, дефолтные значения приходят как false
                $data = $event->getData();
                if (null === $data || false === $data) {
                    $event->setData($defaultData);
                }
            });
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'default_data' => null,
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        yield FormType::class;
    }
}
