<?php

namespace Symfony\UX\Steroids\FileUploading;

interface FilePersister
{
    /**
     * @param string|resource $file File path or file pointer resource (stream)
     * @param string $extension Extension for a persisting file
     *
     * @return string File name (or file path) of a persisted file
     */
    public function persist($file, string $extension): string;
}
