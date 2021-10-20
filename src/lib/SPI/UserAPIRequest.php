<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\SPI;

abstract class UserAPIRequest extends Request
{
    /** @var string */
    public $source = '';

    /** @var string */
    public $xmlBody = '';
}

class_alias(UserAPIRequest::class, 'EzSystems\EzRecommendationClient\SPI\UserAPIRequest');
