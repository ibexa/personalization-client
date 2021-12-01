<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Event\Subscriber;

use Ibexa\Contracts\Core\Repository\ContentService as ContentServiceInterface;
use Ibexa\Contracts\Core\Repository\LocationService as LocationServiceInterface;
use Ibexa\Contracts\Core\Repository\Values\Content\Location;
use Ibexa\PersonalizationClient\Helper\ContentHelper;
use Ibexa\PersonalizationClient\Helper\LocationHelper;
use Ibexa\PersonalizationClient\Service\NotificationService;

abstract class AbstractRepositoryEventSubscriber extends AbstractCoreEventSubscriber
{
    /** @var \Ibexa\Contracts\Core\Repository\ContentService */
    protected $contentService;

    /** @var \Ibexa\Contracts\Core\Repository\LocationService */
    protected $locationService;

    /** @var \Ibexa\PersonalizationClient\Helper\LocationHelper */
    protected $locationHelper;

    /** @var \Ibexa\PersonalizationClient\Helper\ContentHelper */
    protected $contentHelper;

    public function __construct(
        NotificationService $notificationService,
        ContentServiceInterface $contentService,
        LocationServiceInterface $locationService,
        LocationHelper $locationHelper,
        ContentHelper $contentHelper
    ) {
        parent::__construct($notificationService);
        $this->contentService = $contentService;
        $this->locationService = $locationService;
        $this->locationHelper = $locationHelper;
        $this->contentHelper = $contentHelper;
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    protected function updateLocationSubtree(Location $location, string $method, string $action): void
    {
        $subtree = $this->locationService->loadLocationChildren($location);

        /** @var \Ibexa\Contracts\Core\Repository\Values\Content\Location $content */
        foreach ($subtree as $content) {
            $this->notificationService->sendNotification(
                $method,
                $action,
                $content->getContentInfo()
            );
        }
    }
}

class_alias(AbstractRepositoryEventSubscriber::class, 'EzSystems\EzRecommendationClient\Event\Subscriber\AbstractRepositoryEventSubscriber');
