<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PersonalizationClient\FieldType;

use Ibexa\Contracts\Core\Repository\ContentService;
use Ibexa\Contracts\Core\Repository\Repository;
use Ibexa\Contracts\Core\Repository\Values\Content\Field;
use Ibexa\Core\FieldType\Null\Value as NullValue;
use Ibexa\Core\FieldType\Value;
use Ibexa\Core\Repository\Values\Content\Content;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\DestinationValueAwareInterface;
use Ibexa\PersonalizationClient\FieldType\DestinationContentNormalizerDispatcher;
use Ibexa\PersonalizationClient\FieldType\DestinationContentNormalizerDispatcherInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Ibexa\PersonalizationClient\FieldType\DestinationContentNormalizerDispatcher
 */
final class DestinationContentNormalizerDispatcherTest extends TestCase
{
    private DestinationContentNormalizerDispatcherInterface $destinationContentNormalizerDispatcher;

    /** @var \Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\DestinationValueAwareInterface|\PHPUnit\Framework\MockObject\MockObject */
    private DestinationValueAwareInterface $valueNormalizer;

    /** @var \Ibexa\Contracts\Core\Repository\ContentService|\PHPUnit\Framework\MockObject\MockObject */
    private ContentService $contentService;

    /** @var \Ibexa\Contracts\Core\Repository\Repository|\PHPUnit\Framework\MockObject\MockObject */
    private Repository $repository;

    protected function setUp(): void
    {
        $this->contentService = $this->createMock(ContentService::class);
        $this->repository = $this->createMock(Repository::class);
        $this->valueNormalizer = $this->createMock(DestinationValueAwareInterface::class);
        $this->destinationContentNormalizerDispatcher = new DestinationContentNormalizerDispatcher(
            $this->contentService,
            $this->repository,
            [
                $this->valueNormalizer,
            ],
        );
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
     */
    public function testDispatchReturnNormalizedValue(): void
    {
        $value = $this->getFieldValue(12345);

        $this->configureContentServiceToReturnDestinationContent(12345);
        $this->configureValueNormalizerToReturnIsSupportedNormalizer($value, true);
        $this->configureValueNormalizerToReturnNormalizedValue($value, 12345);

        self::assertEquals(
            12345,
            $this->destinationContentNormalizerDispatcher->dispatch(123)
        );
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
     */
    public function testDispatchReturnNullWhenSupportedNormalizerNotFound(): void
    {
        $value = $this->getFieldValue(12345);

        $this->configureContentServiceToReturnDestinationContent(12345);
        $this->configureValueNormalizerToReturnIsSupportedNormalizer($value, false);

        self::assertNull($this->destinationContentNormalizerDispatcher->dispatch(123));
    }

    private function configureContentServiceToReturnDestinationContent(int $fieldValue): void
    {
        $destinationContent = new Content(
            [
                'internalFields' => [
                    new Field(
                        [
                            'id' => 1,
                            'fieldDefIdentifier' => 'foo',
                            'value' => $this->getFieldValue($fieldValue),
                            'languageCode' => 'pl',
                            'fieldTypeIdentifier' => 'foo',
                        ]
                    ),
                ],
            ]
        );

        $this->repository
            ->expects(self::atLeastOnce())
            ->method('sudo')
            ->with(static function () {})
            ->willReturn($destinationContent);
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
