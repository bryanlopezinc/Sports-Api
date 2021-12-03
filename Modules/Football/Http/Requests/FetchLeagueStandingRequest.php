<?php

declare(strict_types=1);

namespace Module\Football\Http\Requests;

use App\Rules\ResourceIdRule;
use App\Http\Requests\FormRequest;
use Illuminate\Validation\Validator;
use Module\Football\Rules\SeasonRule;
use Module\Football\ValueObjects\TeamId;
use App\Exceptions\InvalidResourceIdException;

final class FetchLeagueStandingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'league_id' => ['required', 'int', new ResourceIdRule],
            'season'    => ['required', new SeasonRule]
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

        $teamIds = explode(',', $this->input('teams'));

        $validator->after(function (Validator $validator) use ($teamIds) {
            foreach ($teamIds as $teamId) {
                try {
                    if (!is_numeric($teamId)) {
                        throw new InvalidResourceIdException;
                    }
                    new TeamId((int) $teamId);
                } catch (InvalidResourceIdException) {
                    $validator->errors()->add('teams', 'Invalid Team Id');
                }
            }
        });

        $validator->after(function (Validator $validator) use ($teamIds) {
            if (collect($teamIds)->duplicatesStrict()->isNotEmpty()) {
                $validator->errors()->add('teams', 'Duplicate team ids in request');
            }
        });
    }
}
