<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use eZ\Publish\Core\FieldType\TextBlock\Value;
use Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\TextLineNormalizer;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;

/**
 * @covers \Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\TextLineNormalizer
 */
final class TextLineNormalizerTest extends AbstractValueNormalizerTestCase
{
    /**
     * @dataProvider provideDataForTestNormalize
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function testNormalizer(string $expected, Value $value): void
    {
        $this->testNormalize($expected, $value);
    }

    /**
     * @return iterable<array{
     *  string,
     *  \eZ\Publish\Core\FieldType\TextLine\Value
     * }>
     */
    public function provideDataForTestNormalize(): iterable
    {
        yield [
            'Lorem Ipsum',
            new Value('Lorem Ipsum'),
        ];

        yield [
            '',
            new Value(),
        ];
    }

    protected function getNormalizer(): ValueNormalizerInterface
    {
        return new TextLineNormalizer();
    }

    protected function getValue(): Value
    {
        return new Value();
    }
}
