<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use DateTime;
use eZ\Publish\Core\FieldType\DateAndTime\Value;
use Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\DateAndTimeNormalizer;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;

/**
 * @covers \Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\DateAndTimeNormalizer
 */
final class DateAndTimeNormalizerTest extends AbstractValueNormalizerTestCase
{
    /**
     * @dataProvider provideDataForTestNormalize
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function testNormalizer(?string $expected, Value $value): void
    {
        $this->testNormalize($expected, $value);
    }

    /**
     * @return iterable<array{
     *  ?string,
     *  \eZ\Publish\Core\FieldType\DateAndTime\Value
     * }>
     */
    public function provideDataForTestNormalize(): iterable
    {
        yield [
            '1609491600',
            new Value(new DateTime('2021-01-01 09:00:00')),
        ];

        yield [
            null,
            new Value(),
        ];
    }

    protected function getNormalizer(): ValueNormalizerInterface
    {
        return new DateAndTimeNormalizer();
    }

    protected function getValue(): Value
    {
        return new Value();
    }
}
