<?php

declare(strict_types=1);

namespace Module\Football\DTO\Builders;

use App\ValueObjects\DateValue;
use App\ValueObjects\ResourceId;
use Module\Football\DTO\Comment;
use Module\User\Dto\Builders\UserBuilder;
use Module\Football\Model\Comment as Model;
use Module\Football\ValueObjects\FixtureId;
use Module\Football\ValueObjects\Comment as UserComment;

final class CommentBuilder extends Builder
{
    public static function fromModel(Model $model): self
    {
        return (new self)
            ->set('id', new ResourceId($model['id']))
            ->set('userComment', new UserComment($model['comment']))
            ->set('commentedBy', UserBuilder::fromModel($model['user'])->build())
            ->set('fixtureId', new FixtureId($model['commentable_id']))
            ->set('time', static::mapDate((string)$model['created_at']));
    }

    protected static function mapDate(string $date): DateValue
    {
        return new class($date) extends DateValue
        {
            protected string $format = 'Y-m-d H:i:s';
        };
    }

    public function build(): Comment
    {
        return new Comment($this->attributes);
    }
}
