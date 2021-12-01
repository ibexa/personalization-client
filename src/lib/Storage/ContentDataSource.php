<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Storage;

use Ibexa\Contracts\Core\Repository\ContentService;
use Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException;
use Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException;
use Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException;
use Ibexa\Contracts\Core\Repository\SearchService;
use Ibexa\Contracts\Core\Repository\Values\Content\Content;
use Ibexa\Core\QueryType\QueryType;
use Ibexa\Contracts\PersonalizationClient\Criteria\CriteriaInterface;
use Ibexa\Contracts\PersonalizationClient\Storage\DataSourceInterface;
use Ibexa\Contracts\PersonalizationClient\Value\ItemInterface;
use Ibexa\PersonalizationClient\Content\DataResolverInterface;
use Ibexa\PersonalizationClient\Exception\ItemNotFoundException;
use Ibexa\PersonalizationClient\Value\Storage\Item;
use Ibexa\PersonalizationClient\Value\Storage\ItemList;
use Ibexa\PersonalizationClient\Value\Storage\ItemType;
use Psr\Log\LoggerInterface;

final class ContentDataSource implements DataSourceInterface
{
    private SearchService $searchService;

    private ContentService $contentService;

    private QueryType $queryType;

    private DataResolverInterface $dataResolver;

    private LoggerInterface $logger;

    public function __construct(
        SearchService $searchService,
        ContentService $contentService,
        QueryType $queryType,
        DataResolverInterface $dataResolver,
        LoggerInterface $logger
    ) {
        $this->searchService = $searchService;
        $this->contentService = $contentService;
        $this->queryType = $queryType;
        $this->dataResolver = $dataResolver;
        $this->logger = $logger;
    }

    public function countItems(CriteriaInterface $criteria): int
    {
        try {
            $query = $this->queryType->getQuery(['criteria' => $criteria]);
            $query->limit = 0;
            $languageFilter = ['languages' => $criteria->getLanguages()];

            return $this->searchService->findContent($query, $languageFilter)->totalCount ?? 0;
        } catch (NotFoundException | InvalidArgumentException $exception) {
            $this->logger->error($exception->getMessage());

            return 0;
        }
    }

    public function fetchItems(CriteriaInterface $criteria): iterable
    {
        try {
            $query = $this->queryType->getQuery(['criteria' => $criteria]);
            $query->performCount = false;
            $query->limit = $criteria->getLimit();
            $query->offset = $criteria->getOffset();
            $languageFilter = ['languages' => $criteria->getLanguages()];

            $items = [];

            foreach ($this->searchService->findContent($query, $languageFilter) as $hit) {
                $items[] = $this->createItem($hit->valueObject);
            }

            return new ItemList($items);
        } catch (NotFoundException | InvalidArgumentException $exception) {
            $this->logger->error($exception->getMessage());

            return new ItemList([]);
        }
    }

    public function fetchItem(string $id, string $language): ItemInterface
    {
        try {
            return $this->createItem(
                $this->contentService->loadContent((int)$id, [$language])
            );
        } catch (UnauthorizedException | NotFoundException $exception) {
            throw new ItemNotFoundException($id, $language, 0, $exception);
        }
    }

    private function createItem(Content $content): ItemInterface
    {
        return new Item(
            (string)$content->id,
            ItemType::fromContentType($content->getContentType()),
            $content->contentInfo->getMainLanguage()->languageCode,
            $this->dataResolver->resolve($content)
        );
    }
}
