<?php

namespace KolodochkaDev\Lathe\Traits;

trait HasPathHelper
{
    public function makeHashFromPath(string $path): string
    {
        return md5($path);
    }

    public function normalizePath(string $path): string
    {
        return str_replace(DIRECTORY_SEPARATOR, '/', $path);
    }
}