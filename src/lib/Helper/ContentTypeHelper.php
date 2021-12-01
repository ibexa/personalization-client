<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Helper;

use Ibexa\Contracts\Core\Repository\ContentService as ContentServiceInterface;
use Ibexa\Contracts\Core\Repository\ContentTypeService as ContentTypeServiceInterface;
use Ibexa\Contracts\Core\Repository\Repository as RepositoryInterface;
use Ibexa\Contracts\Core\Repository\Values\Content\ContentInfo;
use Ibexa\Core\MVC\ConfigResolverInterface;
use Ibexa\PersonalizationClient\Value\Parameters;

final class ContentTypeHelper
{
    /** @var \Ibexa\Contracts\Core\Repository\ContentTypeService */
    private $contentTypeService;

    /** @var \Ibexa\Contracts\Core\Repository\ContentService */
    private $contentService;

    /** @var \Ibexa\Core\MVC\ConfigResolverInterface */
    private $configResolver;

    /** @var \Ibexa\Contracts\Core\Repository\Repository */
    private $repository;

    public function __construct(
        ContentTypeServiceInterface $contentTypeService,
        ContentServiceInterface $contentService,
        RepositoryInterface $repository,
        ConfigResolverInterface $configResolver
    ) {
        $this->contentTypeService = $contentTypeService;
        $this->contentService = $contentService;
        $this->configResolver = $configResolver;
        $this->repository = $repository;
    }

    /**
     * Returns ContentType ID based on $contentType name.
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     */
    public function getContentTypeId(string $contentType): int
    {
        return $this->contentTypeService->loadContentTypeByIdentifier($contentType)->id;
    }

    /**
     * Returns ContentType identifier based on $contentId.
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function getContentTypeIdentifier(int $contentId): string
    {
        return $this->contentTypeService->loadContentType(
            $this->contentService
                ->loadContent($contentId)
                ->contentInfo
                ->contentTypeId
        )->identifier;
    }

    /**
     * @throws \Exception
     */
    public function isContentTypeExcluded(ContentInfo $contentInfo): bool
    {
        $contentType = $this->repository->sudo(function () use ($contentInfo) {
            return $this->contentTypeService->loadContentType($contentInfo->contentTypeId);
        });

        return !\in_array(
            $contentType->identifier,
            $this->configResolver->getParameter('included_content_types', Parameters::NAMESPACE)
        );
    }
}

class_alias(ContentTypeHelper::class, 'EzSystems\EzRecommendationClient\Helper\ContentTypeHelper');
