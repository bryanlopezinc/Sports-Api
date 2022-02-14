<?php

declare(strict_types=1);

namespace Module\Football\Http\Requests;

use App\Rules\ResourceIdRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Module\Football\Rules\SeasonRule;
use Module\Football\Rules\LeagueStandingFieldsRule;

final class FetchLeagueStandingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'league_id' => ['required', new ResourceIdRule()],
            'teams.*'   => ['sometimes', new ResourceIdRule()],
            'season'    => ['required', new SeasonRule],
            'fields'    => ['sometimes', 'filled', new LeagueStandingFieldsRule]
        ];
    }

    /**
     * @param Validator $validator
     */
    public function withValidator($validator): void
    {
        if (!$this->filled('teams')) {
            return;
        }

        $validator->after(function (Validator $validator): void {
            if (collect($this->input('teams'))->duplicatesStrict()->isNotEmpty()) {
                $validator->errors()->add('teams', 'Duplicate team ids in request');
            }
        });
    }
}
