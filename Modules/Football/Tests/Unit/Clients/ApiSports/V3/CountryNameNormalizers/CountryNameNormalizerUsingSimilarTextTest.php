<?php

declare(strict_types=1);

namespace Module\Football\Tests\Unit\Clients\ApiSports\V3\CountryNameNormalizers;

use App\ValueObjects\Country;
use Module\Football\Clients\ApiSports\V3\CountryNameNormalizers\CountryNameNormalizerUsingSimilarText;
use PHPUnit\Framework\TestCase;

class CountryNameNormalizerUsingSimilarTextTest extends TestCase
{
    /**
     * @return array<string, string>
     */
    public function countriesData()
    {
        return [
            'ivory coast'                       => Country::VALID['CIV'],
            'cote d\'ivoire'                    => Country::VALID['CIV'],
            'cocos island'                      => Country::VALID['CCK'],
            'bosnia'                            => Country::VALID['BIH'],
            'new-zealand'                       => Country::VALID['NZL'],
            'Congo Republic'                    => Country::VALID['COD'],
            'niger republic'                    => Country::VALID['NER'],
            'Czechia (Czech Republic)'          => Country::VALID['CZE'],
            'czech'                             => Country::VALID['CZE'],
            'Democratic Republic of the Congo'  => Country::VALID['COD'],
            'Central African Republic'          => Country::VALID['CAF'],
            'Dominica'                          => Country::VALID['DMA'],
            'Dominican Republic'                => Country::VALID['DOM'],
            'Guinea'                            => Country::VALID['GIN'],
            'Guinea BIssau'                     => Country::VALID['GNB'],
            'Netherlands Antilles'              => Country::VALID['ANT'],
            'Netherlands-Antilles'              => Country::VALID['ANT'],
            'Netherlands'                       => Country::VALID['NLD'],
            'Usa'                               => Country::VALID['USA'],
            'Syrian Arab Republic'              => Country::VALID['SYR'],
            'Cabo verde'                        => Country::VALID['CCPV']
        ];
    }

    public function test_normalize_country_names(): void
    {
        foreach ($this->countriesData() as $input => $expected) {
            $country = new Country(
                (string) new CountryNameNormalizerUsingSimilarText($input)
            );

            $this->assertTrue(
                $expected === $country->name(),
                sprintf('Expectation failed for country %s. expected %s got %s', $input, $expected, $country->name())
            );
        }
    }
}
