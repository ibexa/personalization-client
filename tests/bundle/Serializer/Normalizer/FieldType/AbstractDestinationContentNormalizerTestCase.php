<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use Ibexa\PersonalizationClient\FieldType\DestinationContentNormalizerDispatcherInterface;

abstract class AbstractDestinationContentNormalizerTestCase extends AbstractValueNormalizerTestCase
{
    /** @var \Ibexa\PersonalizationClient\FieldType\DestinationContentNormalizerDispatcherInterface|\PHPUnit\Framework\MockObject\MockObject */
    protected DestinationContentNormalizerDispatcherInterface $destinationContentNormalizerDispatcher;

    protected function setUp(): void
    {
        $this->destinationContentNormalizerDispatcher = $this->createMock(DestinationContentNormalizerDispatcherInterface::class);
    }

    /**
     * @param array<array<int|string|null>> $valuesMap
     */
    protected function configureDestinationContentNormalizerToReturnExpectedValue(array $valuesMap): void
    {
        $this->destinationContentNormalizerDispatcher
            ->expects(self::atLeastOnce())
            ->method('dispatch')
            ->willReturnMap($valuesMap);
    }
}
