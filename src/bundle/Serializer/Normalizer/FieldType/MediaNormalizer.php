<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use eZ\Publish\Core\FieldType\Media\Value as MediaFileValue;
use eZ\Publish\SPI\Exception\InvalidArgumentType;
use eZ\Publish\SPI\FieldType\Value;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;

final class MediaNormalizer implements ValueNormalizerInterface
{
    public function normalize(Value $value): ?string
    {
        if (!$value instanceof MediaFileValue) {
            throw new InvalidArgumentType('$value', MediaFileValue::class);
        }

        return $value->uri;
    }

    public function supportsValue(Value $value): bool
    {
        return $value instanceof MediaFileValue;
    }
}
