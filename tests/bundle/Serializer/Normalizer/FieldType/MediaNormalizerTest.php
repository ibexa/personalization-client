<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\MediaNormalizer;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;
use Ibexa\Core\FieldType\Media\Value;

/**
 * @covers \Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\MediaNormalizer
 */
final class MediaNormalizerTest extends AbstractValueNormalizerTestCase
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
     *  \eZ\Publish\Core\FieldType\Media\Value
     * }>
     */
    public function provideDataForTestNormalize(): iterable
    {
        yield [
            'public/var/test/1/2/3/4/5/file.invalid',
            new Value(
                [
                    'hasController' => true,
                    'autoplay' => true,
                    'loop' => true,
                    'height' => 500,
                    'width' => 500,
                    'id' => 1,
                    'inputUri' => 'storage/file.invalid',
                    'fileName' => 'file.invalid',
                    'fileSize' => 123456,
                    'mimeType' => 'image/png',
                    'uri' => 'public/var/test/1/2/3/4/5/file.invalid',
                ]
            ),
        ];

        yield [
            null,
            new Value(
                [
                    'hasController' => null,
                    'autoplay' => null,
                    'loop' => null,
                    'height' => null,
                    'width' => null,
                    'id' => null,
                    'inputUri' => null,
                    'fileName' => null,
                    'fileSize' => null,
                    'mimeType' => null,
                    'uri' => null,
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
        return new MediaNormalizer();
    }

    protected function getValue(): Value
    {
        return new Value();
    }
}
