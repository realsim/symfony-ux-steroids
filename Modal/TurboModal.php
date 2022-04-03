<?php

namespace Symfony\UX\Steroids\Modal;

final class TurboModal
{
    public string $id;
    public ?string $targetFrame;

    public function __construct(string $id, ?string $targetFrame)
    {
        $this->id = $id;
        $this->targetFrame = $targetFrame;
    }
}
