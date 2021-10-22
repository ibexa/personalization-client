<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PersonalizationClient\Storage;

use Ibexa\Contracts\PersonalizationClient\Storage\DataSourceInterface;
use Ibexa\Contracts\PersonalizationClient\Value\ItemListInterface;
use Ibexa\PersonalizationClient\Storage\InMemoryDataSource;

final class InMemoryDataSourceTest extends AbstractItemTestCase
{
    protected function createDataSource(ItemListInterface $itemList): DataSourceInterface
    {
        return new InMemoryDataSource($itemList);
    }
}
