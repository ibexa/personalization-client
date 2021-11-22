<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use eZ\Publish\Core\FieldType\Relation\Value as RelationValue;
use eZ\Publish\SPI\Exception\InvalidArgumentType;
use eZ\Publish\SPI\FieldType\Value;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;
use Ibexa\PersonalizationClient\FieldType\DestinationContentNormalizerDispatcherInterface;

final class RelationNormalizer implements ValueNormalizerInterface
{
    private DestinationContentNormalizerDispatcherInterface $destinationContentNormalizer;

    public function __construct(DestinationContentNormalizerDispatcherInterface $destinationContentNormalizer)
    {
        $this->destinationContentNormalizer = $destinationContentNormalizer;
    }

    public function normalize(Value $value)
    {
        if (!$value instanceof RelationValue) {
            throw new InvalidArgumentType('$value', RelationValue::class);
        }

        $destinationContentId = $value->destinationContentId;
        if (null !== $destinationContentId) {
            return $this->destinationContentNormalizer->dispatch((int) $destinationContentId);
        }

        return null;
    }

    public function supportsValue(Value $value): bool
    {
        return $value instanceof RelationValue;
    }
}
