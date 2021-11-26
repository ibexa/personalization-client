<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PersonalizationClient\FieldType;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\Repository;
use eZ\Publish\API\Repository\Values\Content\Field;
use eZ\Publish\Core\FieldType\Null\Value as NullValue;
use eZ\Publish\Core\FieldType\Value;
use eZ\Publish\Core\Repository\Values\Content\Content;
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

    /** @var \eZ\Publish\API\Repository\ContentService|\PHPUnit\Framework\MockObject\MockObject */
    private ContentService $contentService;

    /** @var \eZ\Publish\API\Repository\Repository|\PHPUnit\Framework\MockObject\MockObject */
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
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
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
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
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
