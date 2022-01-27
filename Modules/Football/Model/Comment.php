<?php

declare(strict_types=1);

namespace Module\Football\Model;

use Illuminate\Database\Eloquent\Model;

final class Comment extends Model
{
    public const UPDATED_AT = null;
    
    protected $table = 'comments';
    protected $guarded = [];
}