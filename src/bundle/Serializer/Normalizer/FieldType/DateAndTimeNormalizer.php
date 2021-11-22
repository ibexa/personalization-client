<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use eZ\Publish\Core\FieldType\DateAndTime\Value as DateAndTimeValue;
use eZ\Publish\SPI\Exception\InvalidArgumentType;
use eZ\Publish\SPI\FieldType\Value;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;

final class DateAndTimeNormalizer implements ValueNormalizerInterface
{
    public function normalize(Value $value): ?string
    {
        if (!$value instanceof DateAndTimeValue) {
            throw new InvalidArgumentType('$value', DateAndTimeValue::class);
        }

        $dateTime = $value->value;
        if (null !== $dateTime) {
            return $dateTime->format($value->stringFormat);
        }

        return null;
    }

    public function supportsValue(Value $value): bool
    {
        return $value instanceof DateAndTimeValue;
    }
}
