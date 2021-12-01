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
use Ibexa\Core\FieldType\Time\Value as TimeValue;

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
