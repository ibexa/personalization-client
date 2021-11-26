<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use DateTime;
use eZ\Publish\Core\FieldType\Time\Value as TimeValue;
use eZ\Publish\SPI\Exception\InvalidArgumentType;
use eZ\Publish\SPI\FieldType\Value;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;

final class TimeNormalizer implements ValueNormalizerInterface
{
    public function normalize(Value $value): ?string
    {
        if (!$value instanceof TimeValue) {
            throw new InvalidArgumentType('$value', TimeValue::class);
        }

        if (null !== $value->time) {
            return (string) $value;
        }

        return null;
    }

    public function supportsValue(Value $value): bool
    {
        return $value instanceof TimeValue;
    }
}
