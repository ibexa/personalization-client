<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use eZ\Publish\Core\Base\Exceptions\InvalidArgumentType;
use eZ\Publish\Core\FieldType\RelationList\Value as RelationListValue;
use eZ\Publish\SPI\FieldType\Value;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;
use Ibexa\PersonalizationClient\FieldType\DestinationContentNormalizerDispatcherInterface;

final class RelationListNormalizer implements ValueNormalizerInterface
{
    private DestinationContentNormalizerDispatcherInterface $destinationContentNormalizerDispatcher;

    public function __construct(DestinationContentNormalizerDispatcherInterface $destinationContentNormalizerDispatcher)
    {
        $this->destinationContentNormalizerDispatcher = $destinationContentNormalizerDispatcher;
    }

    /**
     * @return array<array<scalar|null>|scalar|null>
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function normalize(Value $value): array
    {
        if (!$value instanceof RelationListValue) {
            throw new InvalidArgumentType('$value', RelationListValue::class);
        }

        $values = [];
        foreach ($value->destinationContentIds as $destinationContentId) {
            $normalizedValue = $this->destinationContentNormalizerDispatcher->dispatch($destinationContentId);
            if (null !== $normalizedValue) {
                $values[] = $normalizedValue;
            }
        }

        return $values;
    }

    public function supportsValue(Value $value): bool
    {
        return $value instanceof RelationListValue;
    }
}
