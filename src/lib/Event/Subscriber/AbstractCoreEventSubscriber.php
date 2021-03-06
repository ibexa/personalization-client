<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Event\Subscriber;

use Ibexa\PersonalizationClient\Service\NotificationService;

abstract class AbstractCoreEventSubscriber
{
    protected const EVENT_PRIORITY = 10;

    /** @var \Ibexa\PersonalizationClient\Service\EventNotificationService */
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
}

class_alias(AbstractCoreEventSubscriber::class, 'EzSystems\EzRecommendationClient\Event\Subscriber\AbstractCoreEventSubscriber');
