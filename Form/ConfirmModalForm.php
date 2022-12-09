<?php

namespace Symfony\UX\Steroids\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\EqualTo;

class ConfirmModalForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (null !== $options['confirm_phrase']) {
            $builder->add('phrase', TextType::class, [
                'block_prefix' => 'phrase',
                'constraints' => [
                    new EqualTo($options['confirm_phrase']),
                ],
            ]);
        }

        $builder->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'confirm_phrase' => null,
        ]);
        $resolver->setAllowedTypes('confirm_phrase', ['string', 'null']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['confirm_phrase'] = $options['confirm_phrase'];
    }

    public function getBlockPrefix(): string
    {
        return 'confirm_modal';
    }
}
