<?php

declare(strict_types=1);

namespace Module\Football\ValueObjects;

use DateTimeZone;
use App\Utils\Config;
use Illuminate\Http\Request;
use Module\Football\Exceptions\InvalidTimeZoneException;

final class TimeZone
{
    public function __construct(private string $timezone)
    {
        $this->validate();
    }

    public static function fromString(string $timezone): static
    {
        return new static($timezone);
    }

    public static function fromRequest(Request $request, string $key): static
    {
        return new static($request->input($key, Config::get('app.timezone')));
    }

    protected function validate(): void
    {
        try {
            new DateTimeZone($this->timezone);
        } catch (\Throwable $th) {
            throw new InvalidTimeZoneException($th->getMessage(), $th->getCode(), $th->getPrevious());
        }
    }

    public function toDateTimeZone(): DateTimeZone
    {
        return new DateTimeZone($this->timezone);
    }
}
