<?php

namespace KolodochkaDev\Lathe;

use KolodochkaDev\Lathe\Traits\HasPathHelper;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class Compiler
{
    use HasPathHelper;

    private string $compilePath;
    private string $sourcePath;

    public function __construct(string $compilePath, string $sourcePath)
    {
        $this->compilePath = $this->normalizePath($compilePath);
        $this->sourcePath = $this->normalizePath($sourcePath);
    }

    public function compile(string $path): void
    {
        $path = $this->normalizePath($path);
        $html = file_get_contents($path);
        $content = "<?php\nreturn <<< HTML\n$html\nHTML;\n";
        file_put_contents("{$this->compilePath}/{$this->makeHashFromPath($path)}.php", $content);
    }

    public function compileDir(): void
    {
        $this->restoreDir();

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->sourcePath, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        /** @var SplFileInfo $file */
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'html') {
                $this->compile($file->getRealPath());
            }
        }
    }

    private function restoreDir(): void
    {
        if (is_dir($this->compilePath)) {
            $this->removeDir($this->compilePath);
        }

        mkdir($this->compilePath);
    }

    private function removeDir(string $dir): void
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isDir()) {
                rmdir($file->getPathname());
            } else {
                unlink($file->getPathname());
            }
        }

        rmdir($dir);
    }
}