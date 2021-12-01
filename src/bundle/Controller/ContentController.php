<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\Controller;

use Ibexa\Contracts\Core\Repository\Repository;
use Ibexa\Contracts\Core\Repository\SearchService as SearchServiceInterface;
use Ibexa\Contracts\Core\Repository\Values\Content\Query;
use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion;
use Ibexa\Rest\Server\Controller as RestController;
use Ibexa\Rest\Server\Exceptions\AuthenticationFailedException;
use Ibexa\PersonalizationClient\Authentication\AuthenticatorInterface;
use Ibexa\PersonalizationClient\Helper\ParamsConverterHelper;
use Ibexa\PersonalizationClient\Service\ContentServiceInterface;
use Ibexa\PersonalizationClient\Value\Content;
use Ibexa\PersonalizationClient\Value\ContentData;
use Ibexa\PersonalizationClient\Value\IdList;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ContentController extends RestController
{
    /** @var \Ibexa\Contracts\Core\Repository\Repository */
    protected $repository;

    /** @var \Ibexa\Core\Repository\SearchService */
    private $searchService;

    /** @var \Ibexa\PersonalizationClient\Authentication\AuthenticatorInterface */
    private $authenticator;

    /** @var \Ibexa\PersonalizationClient\Service\ContentServiceInterface */
    private $contentService;

    public function __construct(
        Repository $repository,
        SearchServiceInterface $searchService,
        AuthenticatorInterface $authenticator,
        ContentServiceInterface $contentService
    ) {
        $this->repository = $repository;
        $this->searchService = $searchService;
        $this->authenticator = $authenticator;
        $this->contentService = $contentService;
    }

    /**
     * Prepares content for ContentData class.
     *
     * @ParamConverter("list_converter")
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
     */
    public function getContentAction(IdList $idList, Request $request): ContentData
    {
        if (!$this->authenticator->authenticate()) {
            throw new AuthenticationFailedException('Access denied: wrong credentials', Response::HTTP_UNAUTHORIZED);
        }

        $requestQuery = $request->query;

        $content = new Content();
        $content->lang = $requestQuery->get('lang');
        $content->fields = $requestQuery->get('fields')
            ? ParamsConverterHelper::getArrayFromString($requestQuery->get('fields'))
            : null;

        $contentItems = $this->repository->sudo(function () use ($requestQuery, $idList, $content) {
            return $this->searchService->findContent(
                $this->getQuery($requestQuery, $idList),
                (!empty($content->lang) ? ['languages' => [$content->lang]] : [])
            )->searchHits;
        });
        $contentData = $this->contentService->prepareContent([$contentItems], $content);

        return new ContentData($contentData);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\ParameterBag ParameterBag $parameterBag
     */
    private function getQuery(ParameterBag $parameterBag, IdList $idList): Query
    {
        $criteria = [new Criterion\ContentId($idList->list)];

        if (!$parameterBag->get('hidden')) {
            $criteria[] = new Criterion\Visibility(Criterion\Visibility::VISIBLE);
        }

        if ($parameterBag->has('lang')) {
            $criteria[] = new Criterion\LanguageCode($parameterBag->get('lang'));
        }

        $query = new Query();
        $query->query = new Criterion\LogicalAnd($criteria);

        return $query;
    }
}

class_alias(ContentController::class, 'EzSystems\EzRecommendationClientBundle\Controller\ContentController');
