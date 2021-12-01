<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer;

use Ibexa\Contracts\Core\FieldType\Value;

interface ValueNormalizerInterface
{
    /**
     * @return array<scalar|null>|scalar|null
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
     */
    public function normalize(Value $value);

    public function supportsValue(Value $value): bool;
}
