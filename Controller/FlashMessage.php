<?php

namespace Symfony\UX\Steroids\Controller;

final class FlashMessage
{
    public const SUCCESS = 'success';
    public const INFO = 'info';
    public const WARNING = 'warning';
    public const ERROR = 'error';

    public string $type;
    public string $text;
    public ?string $title;
    public ?string $link;
    public ?string $linkAnchor;

    public static function success(string $text, ?string $title = null): self
    {
        return new self(self::SUCCESS, $text, $title);
    }

    public static function info(string $text, ?string $title = null): self
    {
        return new self(self::INFO, $text, $title);
    }

    public static function warning(string $text, ?string $title = null): self
    {
        return new self(self::WARNING, $text, $title);
    }

    public static function error(string $text, ?string $title = null): self
    {
        return new self(self::ERROR, $text, $title);
    }

    public function __construct(string $type, string $text, ?string $title, ?string $link = null, ?string $linkAnchor = null)
    {
        $this->type = $type;
        $this->text = $text;
        $this->title = $title;
        $this->link = $link;
        $this->linkAnchor = $linkAnchor;
    }

    public function withLink(string $link, string $anchor): self
    {
        $message = clone $this;
        $message->link = $link;
        $message->linkAnchor = $anchor;

        return $message;
    }
}
