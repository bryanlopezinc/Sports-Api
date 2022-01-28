<?php

declare(strict_types=1);

namespace Module\Football\Model;

use Module\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Comment extends Model
{
    public const UPDATED_AT = null;

    protected $table = 'comments';
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'commented_by_id', 'id');
    }
}