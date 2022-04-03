<?php

namespace Symfony\UX\Steroids\FileUploading;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileUploader
{
    public function upload(UploadedFile $uploadedFile): string;
}
