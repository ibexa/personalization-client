<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Factory;

use Symfony\Component\HttpFoundation\Request;

interface RequestFactoryInterface
{
    public function createRequest(): Request;
}

class_alias(RequestFactoryInterface::class, 'EzSystems\EzRecommendationClient\Factory\RequestFactoryInterface');
