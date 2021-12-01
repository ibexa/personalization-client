<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use Ibexa\Core\FieldType\ISBN\Value;
use Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\ISBNNormalizer;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;

/**
 * @covers \Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\ISBNNormalizer
 */
final class ISBNNormalizerTest extends AbstractValueNormalizerTestCase
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
     *  \eZ\Publish\Core\FieldType\ISBN\Value
     * }>
     */
    public function provideDataForTestNormalize(): iterable
    {
        yield [
            '978-83-900210-1-0 ',
            new Value('978-83-900210-1-0 '),
        ];

        yield [
            '',
            new Value(),
        ];
    }

    protected function getNormalizer(): ValueNormalizerInterface
    {
        return new ISBNNormalizer();
    }

    protected function getValue(): Value
    {
        return new Value();
    }
}
