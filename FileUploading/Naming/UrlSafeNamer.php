<?php
/**
 * Created by simpson <simpsonwork@gmail.com>
 * Date: 30.06.2021
 * Time: 09:07
 */

namespace Symfony\UX\Steroids\FileUploading\Naming;

use Symfony\Component\String\ByteString;
use function sprintf;

class UrlSafeNamer implements Namer
{
    public function name(string $extension): string
    {
        $name = ByteString::fromRandom(16, '0123456789abcdefghijklmnopqrstuvwxyz');

        if ('' === $extension) {
            return $name;
        }

        return sprintf('%s.%s', $name, $extension);
    }
}
