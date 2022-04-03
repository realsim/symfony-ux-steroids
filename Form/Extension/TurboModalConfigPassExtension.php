<?php

namespace Symfony\UX\Steroids\Form\Extension;

use Symfony\UX\Steroids\Modal\TurboModal;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function is_string;
use function is_array;

class TurboModalConfigPassExtension extends AbstractTypeExtension
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();

            if ($form->isRoot() && $form->getConfig()->getOption('compound')) {
                $modalIdFieldName = $form->getConfig()->getOption('turbo_modal_id_field_name');
                $modalTargetFrameFieldName = $form->getConfig()->getOption('turbo_modal_target_frame_field_name');

                $data = $event->getData();

                $modalIdValue = is_string($data[$modalIdFieldName] ?? null) ? $data[$modalIdFieldName] : null;
                $modalTargetFrameValue = is_string($data[$modalTargetFrameFieldName] ?? null) ? $data[$modalTargetFrameFieldName] : null;

                if (null !== $modalIdValue) {
                    $turboModal = new TurboModal($modalIdValue, $modalTargetFrameValue);

                    $request = $this->requestStack->getCurrentRequest();
                    $request->attributes->set('turbo_modal', $turboModal);
                }

                if (is_array($data)) {
                    unset($data[$modalIdFieldName], $data[$modalTargetFrameFieldName]);
                    $event->setData($data);
                }
            }
        });
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        if (!$view->parent && $options['compound']) {
            $view->vars['turbo_modal'] = null;

            $factory = $form->getConfig()->getFormFactory();

            $request = $this->requestStack->getCurrentRequest();
            $turboModal = $request->attributes->get('turbo_modal');

            if ($turboModal instanceof TurboModal) {
                $modalIdForm = $factory->createNamed($options['turbo_modal_id_field_name'], HiddenType::class, $turboModal->id, [
                    'block_prefix' => 'turbo_modal',
                    'mapped' => false,
                ]);

                $view->children[$options['turbo_modal_id_field_name']] = $modalIdForm->createView($view);
                $view->vars['turbo_modal_id'] = $turboModal->id;

                if (null !== $turboModal->targetFrame) {
                    $modalTargetFrameForm = $factory->createNamed($options['turbo_modal_target_frame_field_name'], HiddenType::class, $turboModal->targetFrame, [
                        'block_prefix' => 'turbo_modal',
                        'mapped'       => false,
                    ]);

                    $view->children[$options['turbo_modal_target_frame_field_name']] = $modalTargetFrameForm->createView($view);
                }
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'turbo_modal_id_field_name' => '_turbo_modal_id',
            'turbo_modal_target_frame_field_name' => '_turbo_modal_target_frame',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        yield FormType::class;
    }
}
