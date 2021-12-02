<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use eZ\Publish\Core\Base\Exceptions\InvalidArgumentType;
use eZ\Publish\Core\FieldType\ImageAsset\Value as ImageAssetValue;
use eZ\Publish\SPI\FieldType\Value;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;
use Ibexa\PersonalizationClient\FieldType\DestinationContentNormalizerDispatcherInterface;

final class ImageAssetNormalizer implements ValueNormalizerInterface
{
    private DestinationContentNormalizerDispatcherInterface $destinationContentNormalizer;

    public function __construct(DestinationContentNormalizerDispatcherInterface $destinationContentNormalizer)
    {
        $this->destinationContentNormalizer = $destinationContentNormalizer;
    }

    public function normalize(Value $value): ?string
    {
        if (!$value instanceof ImageAssetValue) {
            throw new InvalidArgumentType('$value', ImageAssetValue::class);
        }

        $destinationContentId = $value->destinationContentId;
        if (null !== $destinationContentId) {
            $imageUri = $this->destinationContentNormalizer->dispatch((int) $destinationContentId);
            if (is_string($imageUri)) {
                return $imageUri;
            }
        }

        return null;
    }

    public function supportsValue(Value $value): bool
    {
        return $value instanceof ImageAssetValue;
    }
}