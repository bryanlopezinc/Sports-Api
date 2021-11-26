<?php

declare(strict_types=1);

namespace Module\Football\Providers;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;

final class ServiceProviders
{
    /**
     * @return array<string>
     */
    public function getDefinedProviders(): array
    {
        $filesystem = new Filesystem;

        return collect($filesystem->allFiles(__DIR__))
            ->map(fn (SplFileInfo $file): string => __NAMESPACE__ . '\\' . str_replace('.php', '', $file->getRelativePathname()))
            ->reject(__CLASS__)
            ->all();
    }
}
