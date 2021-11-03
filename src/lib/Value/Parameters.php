<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Value;

class Parameters
{
    const NAMESPACE = 'ezrecommendation';
    const API_SCOPE = 'api';
    const FIELD_SCOPE = 'field';
}

class_alias(Parameters::class, 'EzSystems\EzRecommendationClient\Value\Parameters');
