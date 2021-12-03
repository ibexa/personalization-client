<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Event\Subscriber;

use Ibexa\Contracts\Core\Repository\Events\ObjectState\SetContentStateEvent;
use Ibexa\PersonalizationClient\Value\EventNotification;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ObjectStateEventSubscriber extends AbstractCoreEventSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            SetContentStateEvent::class => ['onSetContentState', parent::EVENT_PRIORITY],
        ];
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function onSetContentState(SetContentStateEvent $event): void
    {
        $this->notificationService->sendNotification(
            __METHOD__,
            EventNotification::ACTION_UPDATE,
            $event->getContentInfo()
        );
    }
}

class_alias(ObjectStateEventSubscriber::class, 'EzSystems\EzRecommendationClient\Event\Subscriber\ObjectStateEventSubscriber');
