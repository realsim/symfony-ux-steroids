<?php

namespace Symfony\UX\Steroids\FileUploading\Naming;

interface Namer
{
    /**
     * Generates a filename with the given extension
     *
     * @param string $extension
     *
     * @return string
     */
    public function name(string $extension): string;
}
