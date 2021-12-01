<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Exception;

use Ibexa\Contracts\Core\FieldType\Value;
use RuntimeException;
use Throwable;

final class ValueNormalizerNotFoundException extends RuntimeException implements EzRecommendationException
{
    public function __construct(Value $value, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('ValueNormalizer not found for field type value: %s.', get_class($value)),
            $code,
            $previous
        );
    }
}
