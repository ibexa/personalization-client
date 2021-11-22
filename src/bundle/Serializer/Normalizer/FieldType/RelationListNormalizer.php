<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use eZ\Publish\Core\FieldType\RelationList\Value as RelationListValue;
use eZ\Publish\SPI\Exception\InvalidArgumentType;
use eZ\Publish\SPI\FieldType\Value;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;
use Ibexa\PersonalizationClient\FieldType\DestinationContentNormalizerDispatcherInterface;

final class RelationListNormalizer implements ValueNormalizerInterface
{
    private DestinationContentNormalizerDispatcherInterface $destinationContentNormalizer;

    public function __construct(DestinationContentNormalizerDispatcherInterface $destinationContentNormalizer)
    {
        $this->destinationContentNormalizer = $destinationContentNormalizer;
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

        $fields = [];
        foreach ($value->destinationContentIds as $destinationContentId) {
            $normalizedValue = $this->destinationContentNormalizer->dispatch($destinationContentId);
            if (null !== $normalizedValue) {
                $fields[] = $normalizedValue;
            }
        }

        return $fields;
    }

    public function supportsValue(Value $value): bool
    {
        return $value instanceof RelationListValue;
    }
}
