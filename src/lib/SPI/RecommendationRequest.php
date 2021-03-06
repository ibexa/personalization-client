<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\SPI;

/**
 * Class allow sends own Request object parameters to recommendation engine.
 */
abstract class RecommendationRequest extends Request
{
    public const SCENARIO = 'scenario';

    /** @var string */
    public $scenario;
}

class_alias(RecommendationRequest::class, 'EzSystems\EzRecommendationClient\SPI\RecommendationRequest');
