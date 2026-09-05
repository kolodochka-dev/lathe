<?php

namespace KolodochkaDev\Lathe;

use Exception;
use KolodochkaDev\Lathe\Traits\HasPathHelper;
use KolodochkaDev\Lathe\Traits\HasTemplator;

class Templator
{
    use HasPathHelper, HasTemplator;

    private string $compilePath;
    private string $sourcePath;

    public function __construct(string $compilePath, string $sourcePath)
    {
        $this->compilePath = $this->normalizePath($compilePath);
        $this->sourcePath = $this->normalizePath($sourcePath);
    }

    public function view(string $path, array $data = []): string
    {
        $templatePath = "$this->compilePath/" . $this->makeHashFromPath("{$this->sourcePath}/$path") . '.php';
        if (!is_file($templatePath)) {
            throw new Exception("Template $path isn't compiled!");
        }

        $render = function () use ($data, $templatePath) {
            extract($this->getHelpers());
            extract($data);

            return require $templatePath;
        };

        return $render();
    }

    public function getHelpers(): array
    {
        $self = $this;

        return [
            'pl' => fn(...$args) => $self->pl(...$args),
            'loop' => fn(...$args) => $self->loop(...$args),
            'if' => fn(...$args) => $self->if(...$args),
            'mr' => fn(...$args) => $self->mr(...$args),
            'count' => fn(...$args) => $self->count(...$args),
            'view' => fn(...$args) => $self->view(...$args),
        ];
    }
}