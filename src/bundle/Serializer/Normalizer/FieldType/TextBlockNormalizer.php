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
use Ibexa\Core\FieldType\TextBlock\Value as TextBlockValue;

final class TextBlockNormalizer implements ValueNormalizerInterface
{
    public function normalize(Value $value): string
    {
        if (!$value instanceof TextBlockValue) {
            throw new InvalidArgumentType('$value', TextBlockValue::class);
        }

        return $value->text;
    }

    public function supportsValue(Value $value): bool
    {
        return $value instanceof TextBlockValue;
    }
}
