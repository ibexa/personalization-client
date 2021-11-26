<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use eZ\Publish\Core\Base\Exceptions\InvalidArgumentType;
use eZ\Publish\Core\FieldType\Null\Value as NullValue;
use eZ\Publish\SPI\FieldType\Value;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;
use PHPUnit\Framework\TestCase;

abstract class AbstractValueNormalizerTestCase extends TestCase
{
    abstract protected function getNormalizer(): ValueNormalizerInterface;

    abstract protected function getValue(): Value;

    protected function testSupportsValue(): void
    {
        $normalizer = $this->getNormalizer();

        self::assertTrue($normalizer->supportsValue($this->getValue()));
        self::assertFalse($normalizer->supportsValue(new NullValue()));
    }

    /**
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    protected function testThrowExceptionWhenValueIsInvalidType(): void
    {
        $this->expectException(InvalidArgumentType::class);
        $this->expectExceptionMessage(
            sprintf(
                'Argument \'$value\' is invalid: Received \'%s\' instead of expected value of type \'%s\'',
                NullValue::class,
                get_class($this->getValue())
            )
        );
        $this->getNormalizer()->normalize(new NullValue());
    }

    /**
     * @param mixed $expected
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    protected function testNormalize($expected, Value $value): void
    {
        self::assertEquals(
            $expected,
            $this->getNormalizer()->normalize($value)
        );
    }
}
