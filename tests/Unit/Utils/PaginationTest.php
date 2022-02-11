<?php

declare(strict_types=1);

namespace Tests\Unit\Utils;

use App\Utils\PaginationData as Pagination;
use PHPUnit\Framework\TestCase;

class PaginationTest extends TestCase
{
    public function test_will_return_15_when_per_page_value_is_lower_than_15(): void
    {
        $pagination = new Pagination(perPage: Pagination::MIN_PER_PAGE - 1);

        $this->assertEquals(Pagination::MIN_PER_PAGE, $pagination->getPerPage());
    }

    public function test_will_return_30_when_per_page_value_is_higher_than_30(): void
    {
        $pagination = new Pagination(perPage: Pagination::MAX_PER_PAGE + 1);
        $this->assertEquals(Pagination::MAX_PER_PAGE, $pagination->getPerPage());
    }

    public function test_will_return_default_value_when_per_page_is_higher_than_default(): void
    {
        $pagination = new Pagination(perPage: 101, maxItemsPerPage: 100);
        $this->assertEquals(100, $pagination->getPerPage());
    }

    public function test_will_return_default_value_when_per_page_is_lower_than_default(): void
    {
        $pagination = new Pagination(perPage: 4, minItemsPerPage: 5);
        $this->assertEquals(5, $pagination->getPerPage());
    }

    public function test_will_return_correct_per_page_value(): void
    {
        foreach (range(Pagination::MIN_PER_PAGE, Pagination::MAX_PER_PAGE) as $perPage) {
            $pagination = new Pagination(perPage: $perPage);
            $this->assertEquals($perPage, $pagination->getPerPage());
        }
    }

    public function test_returns_one_when_page_request_is_less_than_one(): void
    {
        $pagination = new Pagination(-1);

        $this->assertEquals(1, $pagination->getPage());
    }

    public function test_returns_one_when_page_value_is_greater_than_maximum_page(): void
    {
        $pagination = new Pagination(Pagination::MAX_PAGE + 1);

        $this->assertEquals(1, $pagination->getPage());
    }
}
