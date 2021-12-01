<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use Ibexa\Core\Base\Exceptions\InvalidArgumentType;
use Ibexa\Core\FieldType\ImageAsset\Value as ImageAssetValue;
use Ibexa\Contracts\Core\FieldType\Value;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;
use Ibexa\PersonalizationClient\FieldType\DestinationContentNormalizerDispatcherInterface;

final class ImageAssetNormalizer implements ValueNormalizerInterface
{
    private DestinationContentNormalizerDispatcherInterface $destinationContentNormalizerDispatcher;

    public function __construct(DestinationContentNormalizerDispatcherInterface $destinationContentNormalizerDispatcher)
    {
        $this->destinationContentNormalizerDispatcher = $destinationContentNormalizerDispatcher;
    }

    public function normalize(Value $value): ?string
    {
        if (!$value instanceof ImageAssetValue) {
            throw new InvalidArgumentType('$value', ImageAssetValue::class);
        }

        $destinationContentId = $value->destinationContentId;
        if (null !== $destinationContentId) {
            $imageUri = $this->destinationContentNormalizerDispatcher->dispatch((int) $destinationContentId);
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
