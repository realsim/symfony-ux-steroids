<?php

namespace Symfony\UX\Steroids\Breadcrumbs;

class Item
{
    private string $label;
    private ?string $url;

    public static function link(string $label, string $url): static
    {
        return new static($label, $url);
    }

    public static function text(string $label): static
    {
        return new static($label, null);
    }

    public function __construct(string $label, ?string $url)
    {
        $this->label = $label;
        $this->url = $url;
    }

    public function label(): string
    {
        return $this->label;
    }

    public function url(): ?string
    {
        return $this->url;
    }

    public function isLink(): bool
    {
        return null !== $this->url;
    }
}
