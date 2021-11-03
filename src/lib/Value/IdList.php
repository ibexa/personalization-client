<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Value;

class IdList
{
    /** @var array */
    public $list;
}

class_alias(IdList::class, 'EzSystems\EzRecommendationClient\Value\IdList');
