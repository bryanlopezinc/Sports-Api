<?php

declare(strict_types=1);

namespace Tests\Unit\Utils;

use App\Utils\PaginationData;
use App\Utils\PaginationDataRules as Rules;
use Tests\TestCase;

class PaginationDataRulesTest extends TestCase
{
    public function testDefault(): void
    {
        $default = [
            'page'      => ['nullable', 'int', 'min:1', 'max:' . PaginationData::MAX_PAGE],
            'per_page'  => [
                'nullable',
                'int',
                'min:' . PaginationData::MIN_PER_PAGE,
                'max:' . PaginationData::MAX_PER_PAGE
            ]
        ];

        $this->assertEquals(Rules::default(), $default);
        $this->assertEquals(Rules::new()->toArray(), $default);
    }

    public function test_will_increment_max_age(): void
    {
        $data = Rules::new()->maxPerPage(50)->toArray();

        $this->assertEquals($data['per_page'], [
            'nullable',
            'int',
            'min:' . PaginationData::MIN_PER_PAGE,
            'max:50'
        ]);
    }
}
