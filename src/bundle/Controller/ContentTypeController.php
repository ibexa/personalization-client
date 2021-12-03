<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\Controller;

use Ibexa\Contracts\Core\Repository\LocationService as LocationServiceInterface;
use Ibexa\Contracts\Core\Repository\Repository;
use Ibexa\Contracts\Core\Repository\SearchService as SearchServiceInterface;
use Ibexa\Contracts\Core\Repository\Values\Content\Query;
use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion;
use Ibexa\PersonalizationClient\Authentication\AuthenticatorInterface;
use Ibexa\PersonalizationClient\Helper\SiteAccessHelper;
use Ibexa\PersonalizationClient\Service\ContentServiceInterface;
use Ibexa\PersonalizationClient\Value\Content;
use Ibexa\PersonalizationClient\Value\ContentData;
use Ibexa\PersonalizationClient\Value\IdList;
use Ibexa\Rest\Server\Controller as RestController;
use Ibexa\Rest\Server\Exceptions\AuthenticationFailedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ContentTypeController extends RestController
{
    private const PAGE_SIZE = 10;

    /** @var \Ibexa\Contracts\Core\Repository\Repository */
    protected $repository;

    /** @var \Ibexa\Core\Repository\LocationService */
    private $locationService;

    /** @var \Ibexa\Core\Repository\SearchService */
    private $searchService;

    /** @var \Ibexa\PersonalizationClient\Authentication\AuthenticatorInterface */
    private $authenticator;

    /** @var \Ibexa\PersonalizationClient\Service\ContentServiceInterface */
    private $contentService;

    /** @var \Ibexa\PersonalizationClient\Helper\SiteAccessHelper */
    private $siteAccessHelper;

    public function __construct(
        Repository $repository,
        LocationServiceInterface $locationService,
        SearchServiceInterface $searchService,
        AuthenticatorInterface $authenticator,
        ContentServiceInterface $contentService,
        SiteAccessHelper $siteAccessHelper
    ) {
        $this->repository = $repository;
        $this->locationService = $locationService;
        $this->searchService = $searchService;
        $this->authenticator = $authenticator;
        $this->contentService = $contentService;
        $this->siteAccessHelper = $siteAccessHelper;
    }

    /**
     * Prepares content for ContentData class.
     *
     * @ParamConverter("list_converter")
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function getContentTypeAction(IdList $idList, Request $request): ContentData
    {
        if (!$this->authenticator->authenticate()) {
            throw new AuthenticationFailedException('Access denied: wrong credentials', Response::HTTP_UNAUTHORIZED);
        }

        $content = $this->prepareContentByContentTypeIds($idList->list, $request);

        return new ContentData($content);
    }

    /**
     * Returns paged content based on ContentType ids.
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    private function prepareContentByContentTypeIds(array $contentTypeIds, Request $request): array
    {
        $requestQuery = $request->query;

        $content = new Content();
        $content->lang = $requestQuery->get('lang');
        $content->fields = $requestQuery->get('fields');

        $contentItems = [];

        foreach ($contentTypeIds as $contentTypeId) {
            $contentItems[$contentTypeId] = $this->repository->sudo(function () use ($contentTypeId, $requestQuery, $content) {
                return $this->searchService->findContent(
                    $this->getQuery((int) $contentTypeId, $requestQuery),
                    (!empty($content->lang) ? ['languages' => [$content->lang]] : [])
                )->searchHits;
            });
        }

        return $this->contentService->prepareContent($contentItems, $content);
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    private function getQuery(int $contentTypeId, ParameterBag $parameterBag): Query
    {
        $criteria = [new Criterion\ContentTypeId($contentTypeId)];

        if ($parameterBag->has('path')) {
            $criteria[] = new Criterion\Subtree($parameterBag->get('path'));
        }

        if (!$parameterBag->get('hidden')) {
            $criteria[] = new Criterion\Visibility(Criterion\Visibility::VISIBLE);
        }

        if ($parameterBag->has('sa')) {
            $rootLocationPathString = $this->locationService->loadLocation(
                $this->siteAccessHelper->getRootLocationBySiteAccessName($parameterBag->get('sa'))
            )->pathString;

            $criteria[] = new Criterion\Subtree($rootLocationPathString);
        }

        $query = new Query();

        $pageSize = (int) $parameterBag->get('page_size', self::PAGE_SIZE);
        $page = (int) $parameterBag->get('page', 1);
        $offset = $page * $pageSize - $pageSize;

        $query->query = new Criterion\LogicalAnd($criteria);
        $query->limit = $pageSize;
        $query->offset = $offset;

        return $query;
    }
}

class_alias(ContentTypeController::class, 'EzSystems\EzRecommendationClientBundle\Controller\ContentTypeController');
