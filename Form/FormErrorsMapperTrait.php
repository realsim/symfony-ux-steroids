<?php

namespace Symfony\UX\Steroids\Form;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

trait FormErrorsMapperTrait
{
    /**
     * @var array<string, FormError>
     */
    private array $mappedErrors = [];

    private function addErrorAtPath(string $formName, FormError $error): void
    {
        if (!array_key_exists($formName, $this->mappedErrors)) {
            $this->mappedErrors[$formName] = [];
        }

        $this->mappedErrors[$formName][] = $error;
    }

    private function mapFormErrors(FormInterface $form): void
    {
        foreach ($this->mappedErrors as $formName => $errors) {
            $childForm = $form->get($formName);

            foreach ($errors as $error) {
                $childForm->addError($error);
            }
        }
    }
}
