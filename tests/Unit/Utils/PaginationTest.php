<?php

declare(strict_types=1);

namespace Tests\Unit\Utils;

use App\Utils\PaginationData as Pagination;
use PHPUnit\Framework\TestCase;

class PaginationTest extends TestCase
{
    public function test_returns_default_when_per_page_value_is_too_low(): void
    {
        $pagination = new Pagination(perPage: Pagination::MIN_PER_PAGE - 1);

        $this->assertTrue(Pagination::PER_PAGE === $pagination->getPerPage());
    }

    public function test_returns_default_when_per_page_value_is_too_large(): void
    {
        $pagination = new Pagination(perPage: Pagination::MAX_PER_PAGE + 1);

        $this->assertEquals(Pagination::PER_PAGE, $pagination->getPerPage());
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
