<?php

namespace Symfony\UX\Steroids\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use function is_string;
use function is_callable;

class AsyncChoiceCreationExtension extends AbstractTypeExtension
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'async_creation_allowed' => false,
            'async_creation_route' => null,
            'async_creation_url' => null,
        ]);
        $resolver->setAllowedTypes('async_creation_allowed', 'boolean');
        $resolver->setAllowedTypes('async_creation_route', ['string', 'null']);
        $resolver->setAllowedTypes('async_creation_url', ['callable', 'string', 'null']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (true === $options['async_creation_allowed']) {
            if (true === $options['expanded']) {
                throw new LogicException('Async form choice creation does not support expanded mode.');
            }

            $view->vars['async_creation_allowed'] = true;
            $view->vars['frame_id'] = 'frame_' . $view->vars['id'];

            if (null !== $creationUrl = $this->generateAsyncCreationUrl($options, $view->vars['id'])) {
                $view->vars['async_creation_url'] = $creationUrl;
            } else {
                throw new LogicException('Either "async_creation_route" or "async_creation_url" option must be provided for async creation.');
            }
        } else {
            $view->vars['async_creation_allowed'] = false;
            $view->vars['async_creation_url'] = null;
        }
    }

    private function generateAsyncCreationUrl(array $options, string $widget): ?string
    {
        if (isset($options['async_creation_route'], $options['async_creation_url'])) {
            trigger_error('Either "async_creation_route" or "async_creation_url" option can be provided.', E_USER_WARNING);
        }

        if (isset($options['async_creation_route'])) {
            return $this->urlGenerator->generate(
                $options['async_creation_route'],
                ['widget' => $widget],
                UrlGeneratorInterface::ABSOLUTE_URL,
            );
        }

        if (isset($options['async_creation_url'])) {
            if (is_string($options['async_creation_url'])) {
                return $options['async_creation_url'];
            }

            if (is_callable($options['async_creation_url'])) {
                return $options['async_creation_url']($this->urlGenerator, ['widget' => $widget]);
            }
        }

        return null;
    }

    public static function getExtendedTypes(): iterable
    {
        yield ChoiceType::class;
    }
}
