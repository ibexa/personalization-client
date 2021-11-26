<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use eZ\Publish\Core\FieldType\Image\Value as ImageValue;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentType;
use eZ\Publish\SPI\FieldType\Value;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\DestinationValueAwareInterface;

final class ImageNormalizer implements DestinationValueAwareInterface
{
    public function normalize(Value $value): ?string
    {
        if (!$value instanceof ImageValue) {
            throw new InvalidArgumentType('$value', ImageValue::class);
        }

        return $value->uri;
    }

    public function supportsValue(Value $value): bool
    {
        return $value instanceof ImageValue;
    }
}
