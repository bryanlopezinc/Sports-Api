<?php

declare(strict_types=1);

namespace Module\Football\Http\Requests;

use App\Rules\ResourceIdRule;
use Illuminate\Foundation\Http\FormRequest;
use Module\Football\ValueObjects\Comment;

final class CreateCommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'fixture_id' => ['required', new ResourceIdRule()],
            'comment'    => ['required', 'string', 'filled', 'max:' . Comment::MAX]
        ];
    }
}
