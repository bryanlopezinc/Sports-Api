<?php

declare(strict_types=1);

namespace App\Utils;

use Illuminate\Support\Arr;

final class PaginationDataRules
{
    private string $pageName = 'page';
    private string $perPageName = 'per_page';

    private array $config = [
        'page' => [
            'nullable',
            'int',
            'min:1',
            'max:' . PaginationData::MAX_PAGE
        ],
        'per_page'  => [
            'nullable',
            'int',
            'min' => 'min:' . PaginationData::MIN_PER_PAGE,
            'max' => 'max:' . PaginationData::MAX_PER_PAGE
        ]
    ];

    public static function new(): self
    {
        return new self;
    }

    public static function default(): array
    {
        return (new self)->toArray();
    }

    public function maxPerPage(int $maxPerPage): self
    {
        Arr::set($this->config, 'per_page.max', "max:$maxPerPage");

        return $this;
    }

    public function toArray(): array
    {
        $config = [];

        $config[$this->pageName] = $this->config['page'];
        $config[$this->perPageName] = array_values($this->config['per_page']);

        return $config;
    }
}
