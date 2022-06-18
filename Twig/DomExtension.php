<?php

namespace Symfony\UX\Steroids\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DomExtension extends AbstractExtension
{
    private PropertyAccessorInterface $accessor;
    private RequestStack $requestStack;
    private UrlHelper $urlHelper;

    public function __construct(PropertyAccessorInterface $accessor, RequestStack $requestStack, UrlHelper $urlHelper)
    {
        $this->accessor = $accessor;
        $this->requestStack = $requestStack;
        $this->urlHelper = $urlHelper;
    }

    public function getFunctions(): iterable
    {
        yield new TwigFunction('dom_id', [$this, 'generateDomId']);
        yield new TwigFunction('dom_ref_id', [$this, 'generateRefId']);
    }

    public function generateDomId($object, string $customPrefix = null): string
    {
        if (is_object($object)) {
            $class = get_class($object);
            $idPrefix = $customPrefix ?: 'dom_'.$this->classHash($class);
            $id = $this->accessor->getValue($object, 'id');

            return $idPrefix.'_'.$id;
        }

        if (is_array($object)) {
            $idPrefix = $customPrefix ?: '';
            $id = $this->accessor->getValue($object, '[id]');

            return $idPrefix.'_'.$id;
        }

        throw new \InvalidArgumentException('Only object and arrays are allowed to generate DOM id.');
    }

    public function generateRefId(string $ref = null): string
    {
        if (null === $ref) {
            $request = $this->requestStack->getCurrentRequest();
            if (null === $request) {
                throw new \InvalidArgumentException('$ref was not provided explicitly and the current request is not defined either.');
            }

            $ref = $request->getUri();
        }

        $normalized = strtolower($this->urlHelper->getAbsoluteUrl($ref));

        return 'ref_'.$this->stringHash($normalized);
    }

    private function stringHash(string $input): string
    {
        return substr(hash('sha256', $input), 0, 12);
    }

    private function classHash(string $className): string
    {
        // Переносимость между классами *View и *ListView
        if (str_ends_with($className, 'View')) {
            $className = preg_replace('/(List)?View$/', '', $className);
        }

        return $this->stringHash($className);
    }
}
