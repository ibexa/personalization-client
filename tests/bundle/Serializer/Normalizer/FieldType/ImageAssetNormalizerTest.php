<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use eZ\Publish\Core\FieldType\ImageAsset\Value;
use Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\ImageAssetNormalizer;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;

/**
 * @covers \Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\ImageAssetNormalizer
 */
final class ImageAssetNormalizerTest extends AbstractDestinationContentNormalizerTestCase
{
    /**
     * @dataProvider provideDataForTestNormalize
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function testNormalizer(?int $destinationContentId, ?string $expected, Value $value): void
    {
        if (null !== $destinationContentId) {
            $this->configureDestinationContentNormalizerToReturnExpectedValue([[$destinationContentId, $expected]]);
        }

        $this->testNormalize($expected, $value);
    }

    /**
     * @return iterable<array{
     *  ?int,
     *  ?string,
     *  \eZ\Publish\Core\FieldType\ImageAsset\Value,
     * }>
     */
    public function provideDataForTestNormalize(): iterable
    {
        yield [
            1,
            'public/var/test/1/2/3/4/5/file.invalid',
            new Value(1),
        ];

        yield [
            null,
            null,
            new Value(),
        ];
    }

    protected function getNormalizer(): ValueNormalizerInterface
    {
        return new ImageAssetNormalizer($this->destinationContentNormalizerDispatcher);
    }

    protected function getValue(): Value
    {
        return new Value();
    }
}
