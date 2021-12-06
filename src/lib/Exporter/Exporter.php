<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Exporter;

use Ibexa\Contracts\Core\Repository\ContentTypeService as ContentTypeServiceInterface;
use Ibexa\Contracts\Core\Repository\Repository;
use Ibexa\Contracts\PersonalizationClient\Value\ItemListInterface;
use Ibexa\PersonalizationClient\Content\DataResolverInterface;
use Ibexa\PersonalizationClient\Generator\File\ExportFileGeneratorInterface;
use Ibexa\PersonalizationClient\Helper\ContentHelper;
use Ibexa\PersonalizationClient\Service\ContentServiceInterface;
use Ibexa\PersonalizationClient\Value\Export\FileSettings;
use Ibexa\PersonalizationClient\Value\Export\Parameters;
use Ibexa\PersonalizationClient\Value\Storage\Item;
use Ibexa\PersonalizationClient\Value\Storage\ItemList;
use Ibexa\PersonalizationClient\Value\Storage\ItemType;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generates and export content to Recommendation Server.
 */
final class Exporter implements ExporterInterface
{
    private const API_ENDPOINT_URL = '%s/api/ezp/v2/ez_recommendation/v1/exportDownload/%s';

    /** @var \Ibexa\Contracts\Core\Repository\Repository */
    private $repository;

    /** @var \Ibexa\PersonalizationClient\Generator\File\ExportFileGeneratorInterface */
    private $exportFileGenerator;

    /** @var \Ibexa\Contracts\Core\Repository\ContentTypeService */
    private $contentTypeService;

    /** @var \Ibexa\PersonalizationClient\Service\ContentServiceInterface */
    private $contentService;

    /** @var \Ibexa\PersonalizationClient\Helper\ContentHelper */
    private $contentHelper;

    /** @var \Ibexa\PersonalizationClient\Content\DataResolverInterface */
    private $dataResolver;

    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    public function __construct(
        Repository $repository,
        ExportFileGeneratorInterface $exportFileGenerator,
        ContentTypeServiceInterface $contentTypeService,
        ContentServiceInterface $contentService,
        ContentHelper $contentHelper,
        DataResolverInterface $dataResolver,
        LoggerInterface $logger
    ) {
        $this->repository = $repository;
        $this->exportFileGenerator = $exportFileGenerator;
        $this->contentTypeService = $contentTypeService;
        $this->contentService = $contentService;
        $this->contentHelper = $contentHelper;
        $this->dataResolver = $dataResolver;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function run(Parameters $parameters, string $chunkDir, OutputInterface $output): array
    {
        $urls = [];

        $output->writeln(sprintf('Exporting %s content types', \count($parameters->getItemTypeIdentifierList())));

        foreach ($parameters->getItemTypeIdentifierList() as $id) {
            $contentTypeId = (int)$id;
            $urls[$contentTypeId] = $this->getContentForGivenLanguages($contentTypeId, $chunkDir, $parameters, $output);
        }

        return $urls;
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    private function getContentForGivenLanguages(
        int $contentTypeId,
        string $chunkDir,
        Parameters $parameters,
        OutputInterface $output
    ): array {
        $contents = [];

        foreach ($parameters->languages as $lang) {
            $options = [
                'lang' => $lang,
                'languages' => $parameters->getLanguages(),
                'pageSize' => $parameters->getPageSize(),
                'customerId' => $parameters->getCustomerId(),
                'siteaccess' => $parameters->getSiteaccess(),
            ];
            $count = $this->contentHelper->countContentItemsByContentTypeId($contentTypeId, $options);

            $info = sprintf('Fetching %s items of contentTypeId %s (language: %s)', $count, $contentTypeId, $lang);
            $output->writeln($info);
            $this->logger->info($info);

            for ($i = 1; $i <= ceil($count / $parameters->pageSize); ++$i) {
                $filename = sprintf('%d_%s_%d', $contentTypeId, $lang, $i);
                $chunkPath = $chunkDir . $filename;
                $this->generateFileForContentType($contentTypeId, $chunkPath, $lang, $i, $parameters, $output);

                $contents[$lang] = $this->generateUrlList(
                    $contentTypeId,
                    $lang,
                    $this->generateUrl($parameters->host, $chunkPath, $output)
                );
            }
        }

        return $contents;
    }

    private function generateFileForContentType(
        int $contentTypeId,
        string $chunkPath,
        string $language,
        int $page,
        Parameters $parameters,
        OutputInterface $output
    ): void {
        $contentList = $this->repository->sudo(
            function () use ($contentTypeId, $parameters, $output) {
                return $this->contentService->fetchContent($contentTypeId, $parameters, $output);
            }
        );

        $fileSettings = new FileSettings(
            $this->getItems($contentList),
            (string) $contentTypeId,
            $language,
            $page,
            $chunkPath
        );

        $output->writeln(sprintf(
            'Generating file for contentTypeId: %s, language: %s, chunk: #%s',
            $contentTypeId,
            $language,
            $page,
        ));

        $this->exportFileGenerator->generate($fileSettings);
    }

    /**
     * @param iterable<\eZ\Publish\API\Repository\Values\Content\Content> $contentList
     */
    private function getItems(iterable $contentList): ItemListInterface
    {
        $itemList = [];
        foreach ($contentList as $content) {
            $itemList[] = new Item(
                (string)$content->id,
                ItemType::fromContentType($content->getContentType()),
                $content->contentInfo->getMainLanguage()->languageCode,
                $this->dataResolver->resolve($content)
            );
        }

        return new ItemList($itemList);
    }

    private function generateUrl(string $host, string $chunkPath, OutputInterface $output): string
    {
        $url = sprintf(
            self::API_ENDPOINT_URL,
            $host,
            $chunkPath
        );

        $info = sprintf('Generating url: %s', $url);
        $output->writeln($info);
        $this->logger->info($info);

        return $url;
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     */
    private function generateUrlList(int $contentTypeId, string $lang, string $url): array
    {
        $contentType = $this->contentTypeService->loadContentType($contentTypeId);

        return [
            'urlList' => [$url],
            'contentTypeName' => $contentType->getName($lang) ?? $contentType->getName($contentType->mainLanguageCode),
        ];
    }
}

class_alias(Exporter::class, 'EzSystems\EzRecommendationClient\Exporter\Exporter');
