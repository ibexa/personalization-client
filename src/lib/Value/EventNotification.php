<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Value;

use Ibexa\PersonalizationClient\SPI\Notification;

class EventNotification extends Notification
{
    public const ACTION_UPDATE = 'UPDATE';
    public const ACTION_DELETE = 'DELETE';
}

class_alias(EventNotification::class, 'EzSystems\EzRecommendationClient\Value\EventNotification');
