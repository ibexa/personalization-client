<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Exception;

use Throwable;

final class ItemNotFoundException extends NotFoundException
{
    public function __construct(string $itemId, string $language, int $code = 0, Throwable $previous = null)
    {
        parent::__construct(
            sprintf('Item not found with id: %s and language: %s', $itemId, $language),
            $code,
            $previous
        );
    }
}

class_alias(ItemNotFoundException::class, 'EzSystems\EzRecommendationClient\Exception\ItemNotFoundException');
