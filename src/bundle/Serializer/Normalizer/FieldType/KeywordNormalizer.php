<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use Ibexa\Core\Base\Exceptions\InvalidArgumentType;
use Ibexa\Core\FieldType\Keyword\Value as KeywordValue;
use Ibexa\Contracts\Core\FieldType\Value;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;

final class KeywordNormalizer implements ValueNormalizerInterface
{
    /**
     * @return array<string>
     */
    public function normalize(Value $value): array
    {
        if (!$value instanceof KeywordValue) {
            throw new InvalidArgumentType('$value', KeywordValue::class);
        }

        return $value->values;
    }

    public function supportsValue(Value $value): bool
    {
        return $value instanceof KeywordValue;
    }
}
