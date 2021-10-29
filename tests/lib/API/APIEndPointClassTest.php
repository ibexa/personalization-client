<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PersonalizationClient\API;

use Ibexa\PersonalizationClient\API\AbstractAPI;

class APIEndPointClassTest extends AbstractAPI
{
    const API_NAME = 'api-test';
}

class_alias(APIEndPointClassTest::class, 'EzSystems\EzRecommendationClient\Tests\API\APIEndPointClassTest');
