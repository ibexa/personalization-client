<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Value\Storage;

use Ibexa\Contracts\PersonalizationClient\Value\ItemGroupInterface;
use Ibexa\Contracts\PersonalizationClient\Value\ItemListInterface;

final class ItemGroup implements ItemGroupInterface
{
    private string $identifier;

    private ItemListInterface $itemList;

    public function __construct(
        string $identifier,
        ItemListInterface $itemList
    ) {
        $this->identifier = $identifier;
        $this->itemList = $itemList;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getItems(): ItemListInterface
    {
        return $this->itemList;
    }
}
