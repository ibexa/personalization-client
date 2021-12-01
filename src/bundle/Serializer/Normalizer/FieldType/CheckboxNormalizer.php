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
use Ibexa\Core\FieldType\Checkbox\Value as CheckboxValue;

final class CheckboxNormalizer implements ValueNormalizerInterface
{
    public function normalize(Value $value): bool
    {
        if (!$value instanceof CheckboxValue) {
            throw new InvalidArgumentType('$value', CheckboxValue::class);
        }

        return $value->bool;
    }

    public function supportsValue(Value $value): bool
    {
        return $value instanceof CheckboxValue;
    }
}
