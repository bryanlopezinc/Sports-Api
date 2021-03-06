<?php

declare(strict_types=1);

namespace Module\Football\Prediction;

use App\Rules\ResourceIdRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

final class PredictFixtureRequest extends FormRequest
{
    public const VALID_PREDICTIONS = [
        '1W'    => 'home_wins',
        '2W'    => 'away_wins',
        'D'     => 'draw'
    ];

    /**
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'fixture_id'  => ['required', 'bail', new ResourceIdRule()],
            'prediction'  => ['bail', 'required', 'string', Rule::in(self::VALID_PREDICTIONS)],
        ];
    }
}
