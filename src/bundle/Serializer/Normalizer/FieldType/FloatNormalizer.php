<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use Ibexa\Contracts\Core\FieldType\Value;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;
use Ibexa\Core\Base\Exceptions\InvalidArgumentType;
use Ibexa\Core\FieldType\Float\Value as FloatValue;

final class FloatNormalizer implements ValueNormalizerInterface
{
    public function normalize(Value $value): ?float
    {
        if (!$value instanceof FloatValue) {
            throw new InvalidArgumentType('$value', FloatValue::class);
        }

        return $value->value;
    }

    public function supportsValue(Value $value): bool
    {
        return $value instanceof FloatValue;
    }
}
