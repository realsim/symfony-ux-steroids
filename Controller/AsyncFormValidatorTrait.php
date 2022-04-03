<?php

namespace Symfony\UX\Steroids\Controller;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

trait AsyncFormValidatorTrait
{
    abstract protected function createForm(string $type, $data = null, array $options = []): FormInterface;

    protected function asyncValidateForm(Request $request, string $type, $data = null, array $options = []): JsonResponse
    {
        $options = array_merge(['csrf_protection' => false], $options);
        $form = $this->createForm($type, $data, $options);
        $form->handleRequest($request);

        if (!$form->isValid()) {
            return new JsonResponse(['valid' => false, 'errors' => $this->flattenFormErrors($form)]);
        }

        return new JsonResponse(['valid' => true]);
    }

    private function flattenFormErrors(Form $form, string $parentName = ''): array
    {
        if ('' !== $parentName) {
            $fullName = sprintf('%s[%s]', $parentName, $form->getName());
        } else {
            $fullName = $form->getName();
        }

        $errors = [];
        $errors[$fullName] = [];

        foreach ($form->getErrors() as $error) {
            $errors[$fullName][] = $error->getMessage();
        }

        foreach ($form->all() as $childForm) {
            if (!$childForm->isValid()) {
                $childErrors = $this->flattenFormErrors($childForm, $fullName);
                $errors += $childErrors;
            }
        }

        return $errors;
    }
}
