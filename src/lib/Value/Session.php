<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Value;

class Session
{
    const RECOMMENDATION_SESSION_KEY = 'recommendation-session-id';
}

class_alias(Session::class, 'EzSystems\EzRecommendationClient\Value\Session');
