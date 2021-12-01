<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Helper;

use Ibexa\Contracts\Core\Repository\ContentService as ContentServiceInterface;
use Ibexa\Contracts\Core\Repository\LocationService as LocationServiceInterface;
use Ibexa\Contracts\Core\Repository\Values\Content\ContentInfo;

final class LocationHelper
{
    /** @var \Ibexa\Contracts\Core\Repository\LocationService */
    private $locationService;

    /** @var \Ibexa\Contracts\Core\Repository\ContentService */
    private $contentService;

    public function __construct(
        LocationServiceInterface $locationService,
        ContentServiceInterface $contentService
    ) {
        $this->locationService = $locationService;
        $this->contentService = $contentService;
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\BadStateException
     */
    public function areLocationsVisible(ContentInfo $contentInfo): bool
    {
        $contentLocations = $this->locationService->loadLocations($contentInfo);

        foreach ($contentLocations as $contentLocation) {
            if (!$contentLocation->hidden) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns location path string based on $contentId.
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function getParentLocationPathString(int $contentId): string
    {
        $content = $this->contentService->loadContent($contentId);
        $location = $this->locationService->loadLocation($content->contentInfo->mainLocationId);
        $parentLocation = $this->locationService->loadLocation($location->parentLocationId);

        return $parentLocation->pathString;
    }
}

class_alias(LocationHelper::class, 'EzSystems\EzRecommendationClient\Helper\LocationHelper');
