<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\UrlNormalizer;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;
use Ibexa\Core\FieldType\Url\Value;

/**
 * @covers \Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\UrlNormalizer
 */
final class UrlNormalizerTest extends AbstractValueNormalizerTestCase
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
     *  \eZ\Publish\Core\FieldType\Url\Value
     * }>
     */
    public function provideDataForTestNormalize(): iterable
    {
        yield [
            'link.invalid',
            new Value('link.invalid', 'foo'),
        ];

        yield [
            null,
            new Value(),
        ];
    }

    protected function getNormalizer(): ValueNormalizerInterface
    {
        return new UrlNormalizer();
    }

    protected function getValue(): Value
    {
        return new Value();
    }
}
