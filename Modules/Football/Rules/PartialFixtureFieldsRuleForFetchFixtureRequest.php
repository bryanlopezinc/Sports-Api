<?php

declare(strict_types=1);

namespace Module\Football\Rules;

use Illuminate\Contracts\Validation\Rule;

final class PartialFixtureFieldsRuleForFetchFixtureRequest implements Rule
{
    public function __construct(private PartialFixtureFieldsRule $mainRule = new PartialFixtureFieldsRule)
    {
        $this->mainRule->addAllowedFields([
            'user.has_predicted',
            'user.prediction',
        ]);
    }

    /**
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->mainRule->passes($attribute, $value);
    }

    /**
     * @return string
     */
    public function message()
    {
        return $this->mainRule->message();
    }
}
