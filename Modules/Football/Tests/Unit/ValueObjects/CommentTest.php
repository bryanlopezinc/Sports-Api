<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\ValueObjects;

use Tests\TestCase;
use Module\Football\ValueObjects\Comment;

class CommentTest extends TestCase
{
    public function test_cannot_be_empty(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Comment('');
    }

    public function test_cannot_exceed_max_length(): void
    {
        $this->expectExceptionCode(505);

        new Comment(str_repeat('A', Comment::MAX + 1));
    }
}
