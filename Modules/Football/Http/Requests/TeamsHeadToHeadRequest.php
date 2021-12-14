<?php

declare(strict_types=1);

namespace Module\Football\Http\Requests;

use App\Rules\ResourceIdRule;
use App\Http\Requests\FormRequest;
use Illuminate\Validation\Validator;
use Module\Football\Rules\TimeZoneRule;
use Module\Football\ValueObjects\TeamId;
use Module\Football\Rules\PartialFixtureFieldsRule;

final class TeamsHeadToHeadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'team_id_1'     => $idRules = ['required', 'int', new ResourceIdRule],
            'team_id_2'     => $idRules,
            'timezone'      => ['sometimes', 'string', new TimeZoneRule],
            'limit'         => ['sometimes', 'int', 'min:1', 'max:50'],
            'fields'        => ['sometimes', 'string', new PartialFixtureFieldsRule]
        ];
    }

    /**
     * @param Validator $validator
     */
    public function withValidator($validator): void
    {
        $validator->after(function (Validator $validator) {

            //Run validation if all other validation passes
            if (!empty($validator->failed())) {
                return;
            }

            $teamIdOne = TeamId::fromRequest($this, 'team_id_1');
            $teamIdTwo = TeamId::fromRequest($this, 'team_id_2');

            if ($teamIdOne->equals($teamIdTwo)) {
                $validator->errors()->add('team_id_1', 'team ids cannot be same')->add('team_id_2', 'team ids cannot be same');
            }
        });
    }
}
