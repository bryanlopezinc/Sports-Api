<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Repository;

use App\Utils\PaginationData;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Module\Football\Factories\CommentFactory;
use Tests\TestCase;
use Module\User\ValueObjects\UserId;
use Module\Football\Model\Comment as Model;
use Module\Football\Repository\CommentsRepository;
use Module\Football\ValueObjects\Comment;
use Module\Football\ValueObjects\FixtureId;

class CommentsRepositoryTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_create_comment(): void
    {
        $repository = new CommentsRepository;

        $this->assertTrue($repository->saveFixtureComment(new FixtureId(23), new Comment('foo bar'), new UserId(90)));

        $this->assertDatabaseHas(new Model, [
            'commentable_type' => $repository::COMMENTABLE_TYPE,
            'comment'          => 'foo bar',
            'commentable_id'   => 23,
            'commented_by_id'  => 90
        ]);
    }

    public function test_get_comments(): void
    {
        $repository = new CommentsRepository;
        $comments = $repository->getFixtureComments(new FixtureId(215), new PaginationData());
        
        $this->assertCount(0, $comments);

        CommentFactory::new()->count(5)->create([
            'commentable_id' => 215
        ]);

        $comments = $repository->getFixtureComments(new FixtureId(215), new PaginationData());
        $this->assertCount(5, $comments);
    }
}
