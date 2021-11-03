<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Value;

use Ibexa\PersonalizationClient\SPI\Content as ContentOptions;

class Content extends ContentOptions
{
}

class_alias(Content::class, 'EzSystems\EzRecommendationClient\Value\Content');
