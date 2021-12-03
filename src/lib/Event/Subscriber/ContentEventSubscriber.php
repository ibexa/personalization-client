<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Event\Subscriber;

use Ibexa\Contracts\Core\Repository\Events\Content\CopyContentEvent;
use Ibexa\Contracts\Core\Repository\Events\Content\DeleteContentEvent;
use Ibexa\Contracts\Core\Repository\Events\Content\HideContentEvent;
use Ibexa\Contracts\Core\Repository\Events\Content\PublishVersionEvent;
use Ibexa\Contracts\Core\Repository\Events\Content\RevealContentEvent;
use Ibexa\Contracts\Core\Repository\Events\Content\UpdateContentMetadataEvent;
use Ibexa\PersonalizationClient\Value\EventNotification;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ContentEventSubscriber extends AbstractCoreEventSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            DeleteContentEvent::class => ['onDeleteContent', parent::EVENT_PRIORITY],
            HideContentEvent::class => ['onHideContent', parent::EVENT_PRIORITY],
            RevealContentEvent::class => ['onRevealContent', parent::EVENT_PRIORITY],
            UpdateContentMetadataEvent::class => ['onUpdateContentMetadata', parent::EVENT_PRIORITY],
            CopyContentEvent::class => ['onCopyContent', parent::EVENT_PRIORITY],
            PublishVersionEvent::class => ['onPublishVersion', parent::EVENT_PRIORITY],
        ];
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function onDeleteContent(DeleteContentEvent $event): void
    {
        $this->notificationService->sendNotification(
            __METHOD__,
            EventNotification::ACTION_DELETE,
            $event->getContentInfo()
        );
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function onHideContent(HideContentEvent $event): void
    {
        $this->notificationService->sendNotification(
            __METHOD__,
            EventNotification::ACTION_DELETE,
            $event->getContentInfo()
        );
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function onRevealContent(RevealContentEvent $event): void
    {
        $this->notificationService->sendNotification(
            __METHOD__,
            EventNotification::ACTION_UPDATE,
            $event->getContentInfo()
        );
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function onUpdateContentMetadata(UpdateContentMetadataEvent $event): void
    {
        $this->notificationService->sendNotification(
            __METHOD__,
            EventNotification::ACTION_UPDATE,
            $event->getContent()->contentInfo
        );
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function onCopyContent(CopyContentEvent $event): void
    {
        $event->getDestinationLocationCreateStruct();

        $this->notificationService->sendNotification(
            __METHOD__,
            EventNotification::ACTION_UPDATE,
            $event->getContent()->contentInfo
        );
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function onPublishVersion(PublishVersionEvent $event): void
    {
        $this->notificationService->sendNotification(
            __METHOD__,
            EventNotification::ACTION_UPDATE,
            $event->getContent()->contentInfo
        );
    }
}

class_alias(ContentEventSubscriber::class, 'EzSystems\EzRecommendationClient\Event\Subscriber\ContentEventSubscriber');
