<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Service;

use eZ\Publish\Core\Repository\Values\Content\Content as CoreContent;
use Ibexa\PersonalizationClient\SPI\Content;
use Ibexa\PersonalizationClient\SPI\Content as ContentOptions;
use Ibexa\PersonalizationClient\Value\ExportParameters;
use Symfony\Component\Console\Output\OutputInterface;

interface ContentServiceInterface
{
    public function fetchContent(int $contentTypeId, ExportParameters $parameters, OutputInterface $output): array;

    /**
     * Prepare content array.
     */
    public function fetchContentItems(int $contentTypeId, ExportParameters $parameters, OutputInterface $output): array;

    public function prepareContent(array $data, Content $options, ?OutputInterface $output = null): array;

    public function setContent(CoreContent $content, ContentOptions $options): array;
}

class_alias(ContentServiceInterface::class, 'EzSystems\EzRecommendationClient\Service\ContentServiceInterface');
