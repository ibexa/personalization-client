<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Service;

use Ibexa\Contracts\Core\Repository\ContentService as APIContentServiceInterface;
use Ibexa\Contracts\Core\Repository\ContentTypeService as ContentTypeServiceInterface;
use Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException;
use Ibexa\Contracts\Core\Repository\LocationService as LocationServiceInterface;
use Ibexa\Contracts\Core\Repository\Values\Content\Content as APIContent;
use Ibexa\Contracts\Core\Repository\Values\Content\ContentInfo;
use Ibexa\Contracts\Core\Repository\Values\ContentType\ContentType as APIContentType;
use Ibexa\Core\Repository\Values\Content\Content as CoreContent;
use Ibexa\PersonalizationClient\Field\Value;
use Ibexa\PersonalizationClient\Helper\ContentHelper;
use Ibexa\PersonalizationClient\SPI\Content as ContentOptions;
use Ibexa\PersonalizationClient\Value\ExportParameters;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\RouterInterface;

final class ContentService implements ContentServiceInterface
{
    /** @var \Ibexa\Contracts\Core\Repository\ContentService */
    private $contentService;

    /** @var \Ibexa\Contracts\Core\Repository\ContentTypeService */
    private $contentTypeService;

    /** @var \Ibexa\Contracts\Core\Repository\LocationService */
    private $locationService;

    /** @var \Symfony\Component\Routing\RouterInterface */
    private $router;

    /** @var \Ibexa\PersonalizationClient\Helper\ContentHelper */
    private $contentHelper;

    /** @var \Ibexa\PersonalizationClient\Field\Value */
    private $value;

    /** @var int */
    private $defaultAuthorId;

    /** @var string */
    private $defaultSiteAccess;

    public function __construct(
        APIContentServiceInterface $contentService,
        ContentTypeServiceInterface $contentTypeService,
        LocationServiceInterface $locationService,
        RouterInterface $router,
        ContentHelper $contentHelper,
        Value $value,
        int $defaultAuthorId,
        string $defaultSiteAccess
    ) {
        $this->contentService = $contentService;
        $this->contentTypeService = $contentTypeService;
        $this->locationService = $locationService;
        $this->router = $router;
        $this->contentHelper = $contentHelper;
        $this->value = $value;
        $this->defaultAuthorId = $defaultAuthorId;
        $this->defaultSiteAccess = $defaultSiteAccess;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function fetchContent(int $contentTypeId, ExportParameters $parameters, OutputInterface $output): array
    {
        $contentItems = $this->fetchContentItems($contentTypeId, $parameters, $output);

        $output->writeln(sprintf(
            'Preparing content for contentTypeId: %s, language: %s, amount: %s, chunk: #%s',
            $contentTypeId,
            $parameters->lang,
            \count($contentItems),
            $parameters->page
        ));

        $content = $this->prepareContent([$contentTypeId => $contentItems], $parameters, $output);

        unset($contentItems);

        return $content;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function fetchContentItems(int $contentTypeId, ExportParameters $parameters, OutputInterface $output): array
    {
        $output->writeln(sprintf(
            'Fetching content from database for contentTypeId: %s, language: %s, chunk: #%s',
            $contentTypeId,
            $parameters->lang,
            $parameters->page
        ));

        return $this->contentHelper->getContentItems($contentTypeId, $parameters->getProperties());
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function prepareContent(array $data, ContentOptions $options, ?OutputInterface $output = null): array
    {
        $content = [];
        $output = $output ?? new NullOutput();

        foreach ($data as $contentTypeId => $items) {
            $progress = new ProgressBar($output, \count($items));
            $progress->start();

            /** @var \Ibexa\Core\Repository\Values\Content\Content $contentValue */
            foreach ($items as $contentValue) {
                $contentValue = $contentValue->valueObject;
                $content[$contentTypeId][$contentValue->id] = $this->setContent($contentValue, $options);
                $progress->advance();
            }

            $progress->finish();
            $output->writeln('');
        }

        return $content;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\BadStateException
     */
    public function setContent(CoreContent $content, ContentOptions $options): array
    {
        $contentInfo = $content->contentInfo;
        $contentType = $this->contentTypeService->loadContentType($contentInfo->contentTypeId);
        $this->value->setFieldDefinitionsList($contentType);
        $location = $this->locationService->loadLocation($contentInfo->mainLocationId);
        $language = $options->lang ?? $location->contentInfo->mainLanguageCode;

        $uriParams = [
            'locationId' => $location->id,
            'siteaccess' => $options->siteaccess ?? $this->defaultSiteAccess,
        ];

        return [
            'contentId' => $content->id,
            'contentTypeId' => $contentType->id,
            'identifier' => $contentType->identifier,
            'language' => $language,
            'publishedDate' => $content->contentInfo->publishedDate->format('c'),
            'author' => $this->getAuthor($content, $contentType),
            'uri' => $this->router->generate('ez_urlalias', $uriParams),
            'mainLocation' => [
                'href' => '/api/ezp/v2/content/locations' . $location->pathString,
            ],
            'locations' => [
                'href' => '/api/ezp/v2/content/objects/' . $content->id . '/locations',
            ],
            'categoryPath' => $this->getCategoryPaths($contentInfo),
            'fields' => $this->setFields($content, $contentType, $options, $language),
        ];
    }

    /**
     * @return array<string>
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\BadStateException
     */
    private function getCategoryPaths(ContentInfo $contentInfo): array
    {
        $categoryPaths = [];
        $locations = $this->locationService->loadLocations($contentInfo);

        foreach ($locations as $location) {
            $categoryPaths[] = $location->pathString;
        }

        return $categoryPaths;
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    private function setFields(
        CoreContent $content,
        APIContentType $contentType,
        ContentOptions $options,
        string $language
    ): array {
        $fields = [];

        foreach ($this->prepareFields($contentType, $options->fields) as $field) {
            $field = $this->value->getConfiguredFieldIdentifier($field, $contentType);
            $fields[$field] =
                $this->value->getFieldValue($content, $field, $language, $options->getProperties());
        }

        return $fields;
    }

    /**
     * Returns author of the content.
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     */
    private function getAuthor(APIContent $contentValue, APIContentType $contentType): string
    {
        $author = $contentValue->getFieldValue(
            $this->value->getConfiguredFieldIdentifier('author', $contentType)
        );

        if (null === $author) {
            try {
                $ownerId = empty($contentValue->contentInfo->ownerId) ? $this->defaultAuthorId : $contentValue->contentInfo->ownerId;
                $userContentInfo = $this->contentService->loadContentInfo($ownerId);
                $author = $userContentInfo->name;
            } catch (UnauthorizedException $e) {
                $author = '';
            }
        }

        return (string) $author;
    }

    /**
     * Checks if fields are given, if not - returns all of them.
     */
    private function prepareFields(APIContentType $contentType, ?array $fields = null): array
    {
        if ($fields && \count($fields) > 0) {
            return $fields;
        }

        foreach ($contentType->getFieldDefinitions() as $field) {
            $fields[] = $field->identifier;
        }

        return $fields;
    }
}

class_alias(ContentService::class, 'EzSystems\EzRecommendationClient\Service\ContentService');
