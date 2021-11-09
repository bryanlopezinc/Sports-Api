<?php

declare(strict_types=1);

namespace Module\Football\Clients\ApiSports\V3\CountryNameNormalizers;

use Illuminate\Support\Str;
use App\ValueObjects\Country;

final class CountryNameNormalizerUsingSimilarText implements \Stringable
{
    private const ALSO_KNOWN_AS = [
        'Ivory-Coast'              => Country::VALID['CIV'],
        'Ivory Coast'              => Country::VALID['CIV'],
        'Usa'                      => Country::VALID['USA'],
        'Kyrgyzstan'               => Country::VALID['KGZ'],
        'Libyan Arab Jamahiriya'   => Country::VALID['LBY'],
        'Bosnia'                   => Country::VALID['BIH'],
        'Chinese-Taipei'           => Country::VALID['TWN'],
        'Chinese Taipei'           => Country::VALID['TWN'],
        'Swaziland'                => Country::VALID['SWZ'],
        'Syrian Arab Republic'     => Country::VALID['SYR']
    ];

    public function __construct(private string $country)
    {
    }

    public function __toString()
    {
        $countryName = $this->country;

        if (inArray($countryName, Country::VALID)) {
            return $countryName;
        };

        if (array_key_exists($name = Str::title($countryName), self::ALSO_KNOWN_AS)) {
            return self::ALSO_KNOWN_AS[$name];
        };

        $mostSimilarCountryId = collect(Country::VALID)
            ->map(function (string $valid) use ($countryName) {
                similar_text($this->prepareForNormalization($countryName), $this->prepareForNormalization($valid), $percentage);

                return $percentage;
            })
            ->sortDesc()
            ->keys()
            ->first();

        return Country::VALID[$mostSimilarCountryId];
    }

    private function prepareForNormalization(string $country): string
    {
        $country = Str::of($country)->title()->replace(['Republic', 'Democratic'], '');

        //remove all words wrapped in brackets
        return preg_replace("/\([^)]+\)/", '', (string) $country);
    }
}
