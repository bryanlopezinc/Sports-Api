<?php

declare(strict_types=1);

namespace Module\Football\Http\Requests;

use App\Rules\ResourceIdRule;
use Illuminate\Foundation\Http\FormRequest;
use Module\Football\Rules\SeasonRule;

final class FetchLeagueTopAssistsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id'        => ['required', new ResourceIdRule()],
            'season'    => ['required', new SeasonRule]
        ];
    }
}
