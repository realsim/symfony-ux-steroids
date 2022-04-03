<?php

namespace Symfony\UX\Steroids\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Включение режима оператора (админа)
 * В режиме оператора в реализациях конкретных форм могут добавляться дополнительные поля и функциональность.
 */
class OperatorModeExtension extends AbstractTypeExtension
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'operator_mode' => false,
        ]);
        $resolver->setAllowedTypes('operator_mode', 'boolean');
    }

    public static function getExtendedTypes(): iterable
    {
        yield FormType::class;
    }
}
