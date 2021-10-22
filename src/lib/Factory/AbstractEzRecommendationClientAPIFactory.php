<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Factory;

use Ibexa\PersonalizationClient\API\AbstractAPI;
use Ibexa\PersonalizationClient\Client\EzRecommendationClientInterface;

abstract class AbstractEzRecommendationClientAPIFactory
{
    abstract public function buildAPI(string $name, EzRecommendationClientInterface $client): AbstractAPI;
}

class_alias(AbstractEzRecommendationClientAPIFactory::class, 'EzSystems\EzRecommendationClient\Factory\AbstractEzRecommendationClientAPIFactory');
