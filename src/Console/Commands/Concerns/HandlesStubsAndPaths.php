<?php

namespace RCV\Core\Console\Commands\Concerns;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

trait HandlesStubsAndPaths
{
    protected Filesystem $files;

    public function initializeHandlesStubsAndPaths(): void
    {
        $this->files = new Filesystem();
    }

    protected function ensureDirForFile(string $filePath): void
    {
        $this->files->ensureDirectoryExists(dirname($filePath));
    }

    protected function parseName(string $name): array
    {
        $parts = preg_split('#[\\/\\\\]#', trim($name), -1, PREG_SPLIT_NO_EMPTY);
        $rawClass = array_pop($parts);

        $namespaceSegments = array_map(fn($s) => Str::studly($s), $parts);
        $pathSegments = array_map(fn($s) => Str::studly($s), $parts);

        $className = Str::studly($rawClass);
        $namespaceSuffix = $namespaceSegments ? implode('\\', $namespaceSegments) : '';
        $pathSuffix = $pathSegments ? implode('/', $pathSegments) : '';
        $kebabPath = ($pathSuffix ? strtolower($pathSuffix) . '/' : '') . Str::kebab($className);

        return [
            'class'           => $className,
            'namespaceSuffix' => $namespaceSuffix,
            'pathSuffix'      => $pathSuffix,
            'kebabPath'       => $kebabPath,
        ];
    }

    protected function getStubContents(?string $optionName, string $defaultPath): string
    {
        $path = $optionName ? ($this->option($optionName) ?? $defaultPath) : $defaultPath;
        if (! $this->files->exists($path)) {
            $this->error("Stub not found: {$path}");
            exit(1);
        }
        return $this->files->get($path);
    }
}
