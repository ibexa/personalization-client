<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\RelationNormalizer;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;
use Ibexa\Core\FieldType\Relation\Value;

/**
 * @covers \Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\RelationNormalizer
 */
final class RelationNormalizerTest extends AbstractDestinationContentNormalizerTestCase
{
    /**
     * @dataProvider provideDataForTestNormalize
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
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
     *  \eZ\Publish\Core\FieldType\Relation\Value,
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
        return new RelationNormalizer($this->destinationContentNormalizerDispatcher);
    }

    protected function getValue(): Value
    {
        return new Value();
    }
}
