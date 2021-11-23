<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use eZ\Publish\Core\FieldType\Checkbox\Value;
use Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\CheckboxNormalizer;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;

/**
 * @covers \Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\CheckboxNormalizer
 */
final class CheckboxNormalizerTest extends AbstractValueNormalizerTestCase
{
    /**
     * @dataProvider provideDataForTestNormalize
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function testNormalizer(bool $expected, Value $value): void
    {
        $this->testNormalize($expected, $value);
    }

    /**
     * @return iterable<array{
     *  bool,
     *  \eZ\Publish\Core\FieldType\Checkbox\Value
     * }>
     */
    public function provideDataForTestNormalize(): iterable
    {
        yield [
            false,
            new Value(),
        ];

        yield [
            true,
            new Value(true),
        ];
    }

    protected function getNormalizer(): ValueNormalizerInterface
    {
        return new CheckboxNormalizer();
    }

    protected function getValue(): Value
    {
        return new Value();
    }
}
