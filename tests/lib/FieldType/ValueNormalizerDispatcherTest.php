<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PersonalizationClient\FieldType;

use Ibexa\Core\FieldType\Null\Value as NullValue;
use Ibexa\Core\FieldType\Value;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;
use Ibexa\PersonalizationClient\Exception\ValueNormalizerNotFoundException;
use Ibexa\PersonalizationClient\FieldType\ValueNormalizerDispatcher;
use Ibexa\PersonalizationClient\FieldType\ValueNormalizerDispatcherInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ibexa\PersonalizationClient\FieldType\ValueNormalizerDispatcher
 */
final class ValueNormalizerDispatcherTest extends TestCase
{
    private ValueNormalizerDispatcherInterface $valueNormalizerDispatcher;

    /** @var \Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface|\PHPUnit\Framework\MockObject\MockObject */
    private ValueNormalizerInterface $valueNormalizer;

    protected function setUp(): void
    {
        $this->valueNormalizer = $this->createMock(ValueNormalizerInterface::class);
        $this->valueNormalizerDispatcher = new ValueNormalizerDispatcher(
            [
                $this->valueNormalizer,
            ]
        );
    }

    public function testDispatch(): void
    {
        $nullValue = $this->getFieldValue(12345);
        $this->configureValueNormalizerToReturnIsSupportedNormalizer($nullValue, true);
        $this->configureValueNormalizerToReturnNormalizedValue($nullValue, 12345);

        self::assertEquals(
            12345,
            $this->valueNormalizerDispatcher->dispatch($nullValue)
        );
    }

    public function testSupportNormalizerReturnTrueWhenSupportedValueNormalizerFound(): void
    {
        $nullValue = $this->getFieldValue();

        $this->configureValueNormalizerToReturnIsSupportedNormalizer($nullValue, true);
        self::assertTrue($this->valueNormalizerDispatcher->supportsNormalizer($nullValue));
    }

    public function testSupportNormalizerReturnFalseWhenSupportedValueNormalizerNotFound(): void
    {
        $nullValue = $this->getFieldValue();

        $this->configureValueNormalizerToReturnIsSupportedNormalizer($nullValue, false);
        self::assertFalse($this->valueNormalizerDispatcher->supportsNormalizer($nullValue));
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
     */
    public function testThrowValueNormalizerNotFoundException(): void
    {
        $nullValue = $this->getFieldValue();

        $this->expectException(ValueNormalizerNotFoundException::class);
        $this->expectExceptionMessage(sprintf(
            'ValueNormalizer not found for field type value: %s.',
            NullValue::class
        ));

        $this->valueNormalizerDispatcher->dispatch($nullValue);
    }

    private function getFieldValue(?int $value = null): Value
    {
        return new NullValue($value);
    }

    private function configureValueNormalizerToReturnIsSupportedNormalizer(
        Value $value,
        bool $isSupported
    ): void {
        $this->valueNormalizer
            ->expects(self::atLeastOnce())
            ->method('supportsValue')
            ->with($value)
            ->willReturn($isSupported);
    }

    /**
     * @param scalar $normalizedValue
     */
    private function configureValueNormalizerToReturnNormalizedValue(
        Value $value,
        $normalizedValue
    ): void {
        $this->valueNormalizer
            ->expects(self::atLeastOnce())
            ->method('normalize')
            ->with($value)
            ->willReturn($normalizedValue);
    }
}
