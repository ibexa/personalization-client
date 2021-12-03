<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Event\Subscriber;

use Ibexa\Contracts\Core\Repository\ContentService as ContentServiceInterface;
use Ibexa\Contracts\Core\Repository\Events\Trash\RecoverEvent;
use Ibexa\Contracts\Core\Repository\Events\Trash\TrashEvent;
use Ibexa\Contracts\Core\Repository\LocationService as LocationServiceInterface;
use Ibexa\Contracts\Core\Repository\Repository;
use Ibexa\Contracts\Core\Repository\Values\Content\ContentInfo;
use Ibexa\PersonalizationClient\Helper\ContentHelper;
use Ibexa\PersonalizationClient\Helper\LocationHelper;
use Ibexa\PersonalizationClient\Service\NotificationService;
use Ibexa\PersonalizationClient\Value\EventNotification;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class TrashEventSubscriber extends AbstractRepositoryEventSubscriber implements EventSubscriberInterface
{
    /** @var \Ibexa\Contracts\Core\Repository\Repository */
    private $repository;

    public function __construct(
        NotificationService $notificationService,
        ContentServiceInterface $contentService,
        LocationServiceInterface $locationService,
        LocationHelper $locationHelper,
        ContentHelper $contentHelper,
        Repository $repository
    ) {
        parent::__construct($notificationService, $contentService, $locationService, $locationHelper, $contentHelper);

        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RecoverEvent::class => ['onRecover', parent::EVENT_PRIORITY],
            TrashEvent::class => ['onTrash', parent::EVENT_PRIORITY],
        ];
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function onRecover(RecoverEvent $event): void
    {
        $this->updateLocationSubtree(
            $event->getLocation(),
            __METHOD__,
            EventNotification::ACTION_UPDATE
        );

        $this->updateRelations(
            $this->getRelations($event->getLocation()->getContentInfo())
        );
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function onTrash(TrashEvent $event): void
    {
        $this->notificationService->sendNotification(
            __METHOD__,
            EventNotification::ACTION_DELETE,
            $event->getLocation()->getContentInfo()
        );

        $this->updateRelations(
            $this->getRelations($event->getLocation()->getContentInfo())
        );
    }

    /**
     * @return \Ibexa\Contracts\Core\Repository\Values\Content\Relation[]
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function getRelations(ContentInfo $contentInfo): array
    {
        /** Sudo must be used to have access to trash and load content relations, since Client using this EventSubscriber operates as a User without privileges. */
        return $this->repository->sudo(function () use ($contentInfo) {
            return $this->contentService->loadReverseRelations($contentInfo);
        });
    }

    /**
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\Relation[] $relations
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function updateRelations(array $relations): void
    {
        foreach ($relations as $relation) {
            $this->notificationService->sendNotification(
                __METHOD__,
                EventNotification::ACTION_UPDATE,
                $this->contentService->loadContentInfo($relation->destinationContentInfo->id)
            );
        }
    }
}

class_alias(TrashEventSubscriber::class, 'EzSystems\EzRecommendationClient\Event\Subscriber\TrashEventSubscriber');
