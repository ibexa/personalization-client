<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use eZ\Publish\Core\Base\Exceptions\InvalidArgumentType;
use eZ\Publish\Core\FieldType\Integer\Value as IntegerValue;
use eZ\Publish\SPI\FieldType\Value;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;

final class IntegerNormalizer implements ValueNormalizerInterface
{
    public function normalize(Value $value): ?int
    {
        if (!$value instanceof IntegerValue) {
            throw new InvalidArgumentType('$value', IntegerValue::class);
        }

        return $value->value;
    }

    public function supportsValue(Value $value): bool
    {
        return $value instanceof IntegerValue;
    }
}
