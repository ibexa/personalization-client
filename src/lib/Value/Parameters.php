<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Value;

class Parameters
{
    public const NAMESPACE = 'ezrecommendation';
    public const API_SCOPE = 'api';
    public const FIELD_SCOPE = 'field';
}

class_alias(Parameters::class, 'EzSystems\EzRecommendationClient\Value\Parameters');
