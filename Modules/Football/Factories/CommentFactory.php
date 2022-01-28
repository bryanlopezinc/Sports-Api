<?php

declare(strict_types=1);

namespace Module\Football\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Module\Football\Model\Comment;
use Module\Football\Repository\CommentsRepository;
use Module\User\Factories\UserFactory;

final class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'commentable_type' => CommentsRepository::COMMENTABLE_TYPE,
            'comment'          => $this->faker->sentence(3),
            'commentable_id'   => FixtureFactory::new()->toDto()->id()->toInt(),
            'commented_by_id'  => UserFactory::new()->create()->id
        ];
    }
}
