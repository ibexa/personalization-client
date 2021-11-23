<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use eZ\Publish\Core\FieldType\Country\Value;
use Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\CountryNormalizer;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;

/**
 * @covers \Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\CountryNormalizer
 */
final class CountryNormalizerTest extends AbstractValueNormalizerTestCase
{
    /**
     * @dataProvider provideDataForTestNormalize
     *
     * @param array<string> $expected
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function testNormalizer(array $expected, Value $value): void
    {
        $this->testNormalize($expected, $value);
    }

    /**
     * @return iterable<array{
     *  array<string>,
     *  \eZ\Publish\Core\FieldType\Country\Value
     * }>
     */
    public function provideDataForTestNormalize(): iterable
    {
        yield [
            ['Japan', 'Poland', 'France'],
            new Value(
                [
                    'JP' => [
                        'Name' => 'Japan',
                        'Alpha2' => 'JP',
                        'Alpha3' => 'JPN',
                        'IDC' => 81,
                    ],
                    'PL' => [
                        'Name' => 'Poland',
                        'Alpha2' => 'PL',
                        'Alpha3' => 'POL',
                        'IDC' => 82,
                    ],
                    'FR' => [
                        'Name' => 'France',
                        'Alpha2' => 'FR',
                        'Alpha3' => 'FRA',
                        'IDC' => 83,
                    ],
                ]
            ),
        ];

        yield [
            [],
            new Value(),
        ];
    }

    protected function getNormalizer(): ValueNormalizerInterface
    {
        return new CountryNormalizer();
    }

    protected function getValue(): Value
    {
        return new Value();
    }
}
