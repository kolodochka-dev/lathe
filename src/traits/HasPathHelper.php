<?php

namespace KolodochkaDev\Lathe\Traits;

trait HasPathHelper
{
    private function makeHashFromPath(string $path): string
    {
        return md5($path);
    }

    private function normalizePath(string $path): string
    {
        return str_replace(DIRECTORY_SEPARATOR, '/', $path);
    }
}