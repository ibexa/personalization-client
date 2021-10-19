<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Generator\ItemList;

use Ibexa\Contracts\PersonalizationClient\Value\ItemListInterface;
use Ibexa\Contracts\Rest\Output\Generator;

/**
 * @internal
 */
interface ItemListOutputGeneratorInterface
{
    public function generate(Generator $generator, ItemListInterface $itemList): Generator;

    public function getOutput(Generator $generator, ItemListInterface $itemList): string;
}
