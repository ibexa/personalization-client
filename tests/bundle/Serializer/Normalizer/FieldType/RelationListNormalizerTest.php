<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use eZ\Publish\Core\FieldType\RelationList\Value;
use Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\RelationListNormalizer;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;

final class RelationListNormalizerTest extends AbstractDestinationContentNormalizerTestCase
{
    /**
     * @dataProvider provideDataForTestNormalize
     *
     * @param array<int> $destinationContentIds
     * @param array<array<int|string>> $valuesMap
     * @param array<string> $expected
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function testNormalizer(
        array $destinationContentIds,
        array $valuesMap,
        array $expected,
        Value $value
    ): void {
        if (!empty($destinationContentIds)) {
            $this->configureDestinationContentNormalizerToReturnExpectedValue($valuesMap);
        }

        $this->testNormalize($expected, $value);
    }

    /**
     * @return iterable<array{
     *  array<int>,
     *  array<array<int|string>>,
     *  array<string>,
     *  \eZ\Publish\Core\FieldType\RelationList\Value,
     * }>
     */
    public function provideDataForTestNormalize(): iterable
    {
        yield [
            [1],
            [
                [1, 'public/var/test/1/2/3/4/5/file.invalid'],
            ],
            ['public/var/test/1/2/3/4/5/file.invalid'],
            new Value([1]),
        ];

        yield [
            [1, 2],
            [
                [1, 'public/var/test/1/2/3/4/5/file.invalid'],
                [2, 'public/var/test/6/7/8/9/file.invalid'],
            ],
            [
                'public/var/test/1/2/3/4/5/file.invalid',
                'public/var/test/6/7/8/9/file.invalid',
            ],
            new Value([1, 2]),
        ];

        yield [
            [],
            [],
            [],
            new Value(),
        ];
    }

    protected function getNormalizer(): ValueNormalizerInterface
    {
        return new RelationListNormalizer($this->destinationContentNormalizerDispatcher);
    }

    protected function getValue(): Value
    {
        return new Value();
    }
}
