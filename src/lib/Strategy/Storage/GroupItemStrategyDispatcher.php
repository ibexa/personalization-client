<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Strategy\Storage;

use Ibexa\Contracts\PersonalizationClient\Criteria\CriteriaInterface;
use Ibexa\Contracts\PersonalizationClient\Storage\DataSourceInterface;
use Ibexa\Contracts\PersonalizationClient\Value\ItemGroupListInterface;
use Ibexa\PersonalizationClient\Exception\UnsupportedGroupItemStrategy;
use Traversable;

final class GroupItemStrategyDispatcher implements GroupItemStrategyDispatcherInterface
{
    /** @var iterable<\EzSystems\EzRecommendationClient\Strategy\Storage\GroupItemStrategyInterface> */
    private iterable $groupItemStrategies;

    /**
     * @param iterable<\EzSystems\EzRecommendationClient\Strategy\Storage\GroupItemStrategyInterface> $groupItemStrategies
     */
    public function __construct(iterable $groupItemStrategies)
    {
        $this->groupItemStrategies = $groupItemStrategies;
    }

    public function getGroupList(
        DataSourceInterface $source,
        CriteriaInterface $criteria,
        string $groupBy
    ): ItemGroupListInterface {
        $strategies = $this->groupItemStrategies instanceof Traversable
            ? iterator_to_array($this->groupItemStrategies)
            : $this->groupItemStrategies;

        if (!isset($strategies[$groupBy])) {
            throw new UnsupportedGroupItemStrategy(
                $groupBy,
                array_keys($strategies)
            );
        }

        return $strategies[$groupBy]->getGroupList($source, $criteria);
    }
}

class_alias(GroupItemStrategyDispatcher::class, 'EzSystems\EzRecommendationClient\Strategy\Storage\GroupItemStrategyDispatcher');
