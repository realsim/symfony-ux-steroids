<?php
/**
 * Created by simpson <simpsonwork@gmail.com>
 * Date: 30.06.2021
 * Time: 09:08
 */

namespace Symfony\UX\Steroids\FileUploading\Naming;

use function implode;
use function substr;
use function strtolower;

class NestedDirectoryNamer implements Namer
{
    private Namer $fileNamer;
    private int $directoryNameLength;
    private int $nestingLevel;

    public function __construct(Namer $fileNamer, int $directoryNameLength, int $nestingLevel)
    {
        $this->fileNamer = $fileNamer;
        $this->directoryNameLength = $directoryNameLength;
        $this->nestingLevel = $nestingLevel;
    }

    public function name(string $extension): string
    {
        $fileName = $this->fileNamer->name($extension);
        $nestedPath = $this->generateNestedPath($fileName);

        return "$nestedPath/$fileName";
    }

    private function generateNestedPath(string $fileName): string
    {
        $directories = [];
        for ($i = 0; $i < $this->nestingLevel; $i++) {
            $offset = $i * $this->directoryNameLength;
            $directories[] = substr($fileName, $offset, $this->directoryNameLength);
        }

        return strtolower(implode('/', $directories));
    }
}
