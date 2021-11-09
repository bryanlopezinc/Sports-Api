<?php

declare(strict_types=1);

namespace App\ValueObjects;

use DateTimeZone;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class DateValue
{
    protected string $format = 'Y-m-d';

    public function __construct(private string $date)
    {
        $this->validate();
    }

    final protected function validate(): void
    {
        $validator = Validator::make(
            ['date' => $this->date],
            ['date' => ['date', 'date_format:' . $this->format]]
        );

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->toJson());
        }
    }

    public function toCarbon(DateTimeZone|string $timzone = null): Carbon
    {
        return Carbon::parse($this->date, $timzone);
    }
}
