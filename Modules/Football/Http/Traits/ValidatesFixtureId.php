<?php

declare(strict_types=1);

namespace Module\Football\Http\Traits;

use App\Rules\ResourceIdRule;

trait ValidatesFixtureId
{
    public function rules(): array
    {
        return ['required', 'int', new ResourceIdRule];
    }
}
