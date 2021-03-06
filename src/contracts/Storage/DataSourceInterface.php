<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Contracts\PersonalizationClient\Storage;

use Ibexa\Contracts\PersonalizationClient\Criteria\CriteriaInterface;
use Ibexa\Contracts\PersonalizationClient\Value\ItemInterface;

interface DataSourceInterface
{
    /**
     * @return iterable<\Ibexa\Contracts\PersonalizationClient\Value\ItemInterface>
     */
    public function fetchItems(CriteriaInterface $criteria): iterable;

    public function countItems(CriteriaInterface $criteria): int;

    public function fetchItem(string $id, string $language): ItemInterface;
}
