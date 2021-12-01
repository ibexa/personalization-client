<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use Ibexa\Core\FieldType\EmailAddress\Value;
use Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\EmailAddressNormalizer;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;

/**
 * @covers \Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\EmailAddressNormalizer
 */
final class EmailAddressNormalizerTest extends AbstractValueNormalizerTestCase
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
     *  \eZ\Publish\Core\FieldType\EmailAddress\Value
     * }>
     */
    public function provideDataForTestNormalize(): iterable
    {
        yield [
            'foo@link.invalid',
            new Value('foo@link.invalid'),
        ];

        yield [
            '',
            new Value(),
        ];
    }

    protected function getNormalizer(): ValueNormalizerInterface
    {
        return new EmailAddressNormalizer();
    }

    protected function getValue(): Value
    {
        return new Value();
    }
}
