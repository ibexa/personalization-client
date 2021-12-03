<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\TextBlockNormalizer;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;
use Ibexa\Core\FieldType\TextBlock\Value;

/**
 * @covers \Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\TextBlockNormalizer
 */
final class TextBlockNormalizerTest extends AbstractValueNormalizerTestCase
{
    /**
     * @dataProvider provideDataForTestNormalize
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
     */
    public function testNormalizer(string $expected, Value $value): void
    {
        $this->testNormalize($expected, $value);
    }

    /**
     * @return iterable<array{
     *  string,
     *  \eZ\Publish\Core\FieldType\TextBlock\Value
     * }>
     */
    public function provideDataForTestNormalize(): iterable
    {
        yield [
            'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
            new Value('Lorem Ipsum is simply dummy text of the printing and typesetting industry.'),
        ];

        yield [
            '',
            new Value(),
        ];
    }

    protected function getNormalizer(): ValueNormalizerInterface
    {
        return new TextBlockNormalizer();
    }

    protected function getValue(): Value
    {
        return new Value();
    }
}
