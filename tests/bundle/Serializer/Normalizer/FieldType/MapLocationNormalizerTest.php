<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use Ibexa\Core\FieldType\MapLocation\Value;
use Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\MapLocationNormalizer;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;

/**
 * @covers \Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\MapLocationNormalizer
 */
final class MapLocationNormalizerTest extends AbstractValueNormalizerTestCase
{
    /**
     * @dataProvider provideDataForTestNormalize
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
     */
    public function testNormalizer(?string $expected, Value $value): void
    {
        $this->testNormalize($expected, $value);
    }

    /**
     * @return iterable<array{
     *  ?string,
     *  \eZ\Publish\Core\FieldType\MapLocation\Value
     * }>
     */
    public function provideDataForTestNormalize(): iterable
    {
        yield [
            'foo street',
            new Value(
                [
                    'latitude' => 12345.11,
                    'longitude' => 9876512.12,
                    'address' => 'foo street',
                ]
            ),
        ];

        yield [
            null,
            new Value(),
        ];
    }

    protected function getNormalizer(): ValueNormalizerInterface
    {
        return new MapLocationNormalizer();
    }

    protected function getValue(): Value
    {
        return new Value();
    }
}
