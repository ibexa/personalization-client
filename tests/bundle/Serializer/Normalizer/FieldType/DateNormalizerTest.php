<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use DateTime;
use Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\DateNormalizer;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;
use Ibexa\Core\FieldType\Date\Value;

/**
 * @covers \Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\DateNormalizer
 */
final class DateNormalizerTest extends AbstractValueNormalizerTestCase
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
     *  \eZ\Publish\Core\FieldType\Date\Value
     * }>
     */
    public function provideDataForTestNormalize(): iterable
    {
        yield [
            'Friday 01 January 2021',
            new Value(new DateTime('2021-01-01')),
        ];

        yield [
            null,
            new Value(),
        ];
    }

    protected function getNormalizer(): ValueNormalizerInterface
    {
        return new DateNormalizer();
    }

    protected function getValue(): Value
    {
        return new Value();
    }
}
