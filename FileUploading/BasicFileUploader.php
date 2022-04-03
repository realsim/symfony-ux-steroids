<?php

namespace Symfony\UX\Steroids\FileUploading;

use Symfony\UX\Steroids\FileUploading\Naming\Namer;
use Symfony\UX\Steroids\FileUploading\Naming\UrlSafeNamer;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use RuntimeException;

use function is_resource;
use function fclose;
use function fopen;
use function sprintf;

class BasicFileUploader implements FileUploader
{
    private Namer $namer;
    private FilesystemOperator $fs;

    public function __construct(FilesystemOperator $fs, ?Namer $namer = null)
    {
        $this->namer = $namer ?? new UrlSafeNamer();
        $this->fs = $fs;
    }

    public function upload(UploadedFile $uploadedFile): string
    {
        do {
            $filePath = $this->namer->name($uploadedFile->getClientOriginalExtension());
        } while ($this->fs->fileExists($filePath));

        $stream = fopen($uploadedFile->getPathname(), 'rb');
        if (!is_resource($stream)) {
            throw new RuntimeException(sprintf('Unable to read file "%s".', $uploadedFile->getPathname()));
        }

        $this->fs->writeStream($filePath, $stream);
        fclose($stream);

        return $filePath;
    }
}
