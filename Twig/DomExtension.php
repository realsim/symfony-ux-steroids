<?php

namespace Symfony\UX\Steroids\Twig;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DomExtension extends AbstractExtension
{
    private PropertyAccessorInterface $accessor;

    public function __construct(PropertyAccessorInterface $accessor)
    {
        $this->accessor = $accessor;
    }

    public function getFunctions(): iterable
    {
        yield new TwigFunction('dom_id', [$this, 'generateDomId']);
    }

    public function generateDomId($object, string $customPrefix = null): string
    {
        if (is_object($object)) {
            $class = get_class($object);
            $idPrefix = $customPrefix ?: substr(sha1($class), 0, 10);
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
}
