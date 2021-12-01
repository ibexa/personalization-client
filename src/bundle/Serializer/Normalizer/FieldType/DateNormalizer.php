<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use Ibexa\Core\Base\Exceptions\InvalidArgumentType;
use Ibexa\Core\FieldType\Date\Value as DateValue;
use Ibexa\Contracts\Core\FieldType\Value;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;

final class DateNormalizer implements ValueNormalizerInterface
{
    public function normalize(Value $value): ?string
    {
        if (!$value instanceof DateValue) {
            throw new InvalidArgumentType('$value', DateValue::class);
        }

        if (null !== $value->date) {
            return (string) $value;
        }

        return null;
    }

    public function supportsValue(Value $value): bool
    {
        return $value instanceof DateValue;
    }
}
