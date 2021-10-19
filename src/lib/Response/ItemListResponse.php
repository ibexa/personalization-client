<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Response;

use Ibexa\Contracts\PersonalizationClient\Value\ItemListInterface;
use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\PersonalizationClient\Generator\ItemList\ItemListOutputGeneratorInterface;

final class ItemListResponse implements ResponseInterface
{
    private ItemListOutputGeneratorInterface $itemListElementGenerator;

    public function __construct(ItemListOutputGeneratorInterface $itemListElementGenerator)
    {
        $this->itemListElementGenerator = $itemListElementGenerator;
    }

    public function render(Generator $generator, ItemListInterface $itemList): Generator
    {
        return $this->itemListElementGenerator->generate($generator, $itemList);
    }
}
