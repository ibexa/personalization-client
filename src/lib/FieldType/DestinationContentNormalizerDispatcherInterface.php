<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\FieldType;

/**
 * @internal
 */
interface DestinationContentNormalizerDispatcherInterface
{
    /**
     * @return array<scalar|null>|scalar|null
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
     */
    public function dispatch(int $destinationContentId);
}
