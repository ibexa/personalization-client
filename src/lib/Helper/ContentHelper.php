<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Helper;

use Ibexa\Contracts\Core\Repository\ContentService as ContentServiceInterface;
use Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException;
use Ibexa\Contracts\Core\Repository\LocationService as LocationServiceInterface;
use Ibexa\Contracts\Core\Repository\SearchService as SearchServiceInterface;
use Ibexa\Contracts\Core\Repository\Values\Content\Content;
use Ibexa\Contracts\Core\Repository\Values\Content\ContentInfo;
use Ibexa\Contracts\Core\Repository\Values\Content\Query;
use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion;
use Ibexa\Core\MVC\ConfigResolverInterface;
use Ibexa\PersonalizationClient\Value\Parameters;
use Psr\Log\LoggerInterface;

final class ContentHelper
{
    /** @var \eZ\Publish\Api\Repository\SearchService */
    private $searchService;

    /** @var \Ibexa\Contracts\Core\Repository\ContentService */
    private $contentService;

    /** @var \Ibexa\Contracts\Core\Repository\LocationService */
    private $locationService;

    /** @var \Ibexa\Core\MVC\ConfigResolverInterface */
    private $configResolver;

    /** @var \Ibexa\PersonalizationClient\Helper\ContentTypeHelper */
    private $contentTypeHelper;

    /** @var \Ibexa\PersonalizationClient\Helper\SiteAccessHelper */
    private $siteAccessHelper;

    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    public function __construct(
        ContentServiceInterface $contentService,
        LocationServiceInterface $locationService,
        SearchServiceInterface $searchService,
        ConfigResolverInterface $configResolver,
        ContentTypeHelper $contentTypeHelper,
        SiteAccessHelper $siteAccessHelper,
        LoggerInterface $logger
    ) {
        $this->contentService = $contentService;
        $this->locationService = $locationService;
        $this->searchService = $searchService;
        $this->configResolver = $configResolver;
        $this->contentTypeHelper = $contentTypeHelper;
        $this->siteAccessHelper = $siteAccessHelper;
        $this->logger = $logger;
    }

    /**
     * Gets languageCodes based on $content.
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function getLanguageCodes(ContentInfo $contentInfo, ?int $versionNo = null): array
    {
        $version = $this->contentService->loadVersionInfo($contentInfo, $versionNo);

        return $version->languageCodes;
    }

    /**
     * Generates the REST URI of content $contentId.
     */
    public function getContentUri(ContentInfo $contentInfo, ?string $lang = null): string
    {
        return sprintf(
            '%s/api/ezp/v2/ez_recommendation/v1/content/%s%s',
            $this->configResolver->getParameter('host_uri', Parameters::NAMESPACE),
            $contentInfo->id,
            isset($lang) ? '?lang=' . $lang : ''
        );
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function getContent(int $contentId, ?array $languages = null, ?int $versionNo = null): ?Content
    {
        try {
            return $this->contentService->loadContent($contentId, $languages, $versionNo);
        } catch (NotFoundException $exception) {
            $this->logger->error(sprintf('Error while loading Content: %d, message: %s', $contentId, $exception->getMessage()));
            // this is most likely a internal draft, or otherwise invalid, ignoring
            return null;
        }
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function getIncludedContent(int $contentId, ?array $languages = null, ?int $versionNo = null): ?Content
    {
        $content = $this->getContent($contentId, $languages, $versionNo);

        return !$this->contentTypeHelper->isContentTypeExcluded($content->contentInfo) ? $content : null;
    }

    /**
     * Returns total amount of content based on ContentType ids.
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function countContentItemsByContentTypeId(int $contentTypeId, array $options): ?int
    {
        $query = $this->getQuery($contentTypeId, $options);
        $query->limit = 0;

        return $this->searchService->findContent(
            $query,
            (!empty($options['lang']) ? ['languages' => [$options['lang']]] : [])
        )->totalCount;
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function getContentItems(int $contentTypeId, array $options): array
    {
        $query = $this->getQuery($contentTypeId, $options);
        $query->limit = (int) $options['pageSize'];
        $query->offset = $options['page'] * $options['pageSize'] - $options['pageSize'];

        return $this->searchService->findContent(
            $query,
            (!empty($options['lang']) ? ['languages' => [$options['lang']]] : [])
        )->searchHits;
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    private function getQuery(int $contentTypeId, array $options): Query
    {
        $criteria = [
            new Criterion\ContentTypeId($contentTypeId),
        ];

        if (isset($options['path'])) {
            $criteria[] = new Criterion\Subtree($options['path']);
        }

        $criteria[] = $this->generateSubtreeCriteria((int)$options['customerId'], $options['siteaccess']);

        $query = new Query();
        $query->query = new Criterion\LogicalAnd($criteria);

        return $query;
    }

    /**
     * Generates Criterions based on mandatoryId or requested siteAccess.
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    private function generateSubtreeCriteria(?int $customerId, ?string $siteAccess = null): Criterion\LogicalOr
    {
        $siteAccesses = $this->siteAccessHelper->getSiteAccesses($customerId, $siteAccess);

        $subtreeCriteria = [];
        $rootLocations = $this->siteAccessHelper->getRootLocationsBySiteAccesses($siteAccesses);
        foreach ($rootLocations as $rootLocationId) {
            $subtreeCriteria[] = new Criterion\Subtree($this->locationService->loadLocation($rootLocationId)->pathString);
        }

        return new Criterion\LogicalOr($subtreeCriteria);
    }
}

class_alias(ContentHelper::class, 'EzSystems\EzRecommendationClient\Helper\ContentHelper');
