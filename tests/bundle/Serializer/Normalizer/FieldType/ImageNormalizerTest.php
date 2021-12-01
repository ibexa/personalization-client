<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use Ibexa\Core\FieldType\Image\Value;
use Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\ImageNormalizer;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;

/**
 * @covers \Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\ImageNormalizer
 */
final class ImageNormalizerTest extends AbstractValueNormalizerTestCase
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
     *  \eZ\Publish\Core\FieldType\Image\Value
     * }>
     *
     * @throws \Ibexa\Core\Base\Exceptions\InvalidArgumentType
     */
    public function provideDataForTestNormalize(): iterable
    {
        yield [
            'public/var/test/1/2/3/4/5/file.invalid',
            new Value(
                [
                    'id' => 1,
                    'alternativeText' => 'Test image',
                    'fileName' => 'file.invalid',
                    'fileSize' => 123456,
                    'uri' => 'public/var/test/1/2/3/4/5/file.invalid',
                    'imageId' => '',
                    'inputUri' => 'storage/file.invalid',
                    'width' => 120,
                    'height' => 80,
                    'additionalData' => [],
                ]
            ),
        ];

        yield [
            null,
            new Value(
                [
                    'id' => null,
                    'alternativeText' => null,
                    'fileName' => null,
                    'fileSize' => null,
                    'uri' => null,
                    'imageId' => null,
                    'inputUri' => null,
                    'width' => null,
                    'height' => null,
                    'additionalData' => [],
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
        return new ImageNormalizer();
    }

    protected function getValue(): Value
    {
        return new Value();
    }
}
