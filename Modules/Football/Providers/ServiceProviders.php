<?php

declare(strict_types=1);

namespace Module\Football\Providers;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;

final class ServiceProviders
{
    protected array $merge = [
        \Module\Football\Prediction\Providers\ServiceProvider::class,
        \Module\Football\Prediction\Providers\EventServiceProvider::class,
        \Module\Football\Prediction\Providers\FixturePredictionsContextualBindingServiceProvider::class,
        \Module\Football\News\ServiceProvider::class
    ];

    /**
     * @return array<string>
     */
    public function getDefinedProviders(): array
    {
        $filesystem = new Filesystem;

        return collect($filesystem->allFiles(__DIR__))
            ->map(fn (SplFileInfo $file): string => __NAMESPACE__ . '\\' . str_replace('.php', '', $file->getRelativePathname()))
            ->merge($this->merge)
            ->reject(__CLASS__)
            ->all();
    }
}
