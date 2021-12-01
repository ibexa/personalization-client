<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use Ibexa\Core\FieldType\Keyword\Value;
use Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\KeywordNormalizer;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;

/**
 * @covers \Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\KeywordNormalizer
 */
final class KeywordNormalizerTest extends AbstractValueNormalizerTestCase
{
    /**
     * @dataProvider provideDataForTestNormalize
     *
     * @param array<string> $expected
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
     */
    public function testNormalizer(array $expected, Value $value): void
    {
        $this->testNormalize($expected, $value);
    }

    /**
     * @return iterable<array{
     *  array<string>,
     *  \eZ\Publish\Core\FieldType\Keyword\Value
     * }>
     */
    public function provideDataForTestNormalize(): iterable
    {
        yield [
            ['foo'],
            new Value('foo'),
        ];

        yield [
            ['foo', 'bar', 'baz'],
            new Value(['foo', 'bar', 'baz']),
        ];

        yield [
            [],
            new Value(),
        ];
    }

    protected function getNormalizer(): ValueNormalizerInterface
    {
        return new KeywordNormalizer();
    }

    protected function getValue(): Value
    {
        return new Value();
    }
}
