<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use Ibexa\Core\Base\Exceptions\InvalidArgumentType;
use Ibexa\Core\FieldType\TextLine\Value as TextLineValue;
use Ibexa\Contracts\Core\FieldType\Value;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;

final class TextLineNormalizer implements ValueNormalizerInterface
{
    public function normalize(Value $value): string
    {
        if (!$value instanceof TextLineValue) {
            throw new InvalidArgumentType('$value', TextLineValue::class);
        }

        return $value->text;
    }

    public function supportsValue(Value $value): bool
    {
        return $value instanceof TextLineValue;
    }
}
