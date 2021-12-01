<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\File;

use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\PersonalizationClient\Generator\ContentListElementGenerator;
use Ibexa\PersonalizationClient\Value\ContentData;
use Psr\Log\LoggerInterface;

final class ExportFileGenerator
{
    /** @var \Ibexa\PersonalizationClient\File\FileManagerInterface */
    private $fileManager;

    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    /** @var \Ibexa\PersonalizationClient\Generator\ContentListElementGenerator */
    private $contentListElementGenerator;

    /** @var \Ibexa\Contracts\Rest\Output\Generator */
    private $outputGenerator;

    public function __construct(
        FileManagerInterface $fileManager,
        LoggerInterface $logger,
        ContentListElementGenerator $contentListElementGenerator,
        Generator $outputGenerator
    ) {
        $this->fileManager = $fileManager;
        $this->logger = $logger;
        $this->contentListElementGenerator = $contentListElementGenerator;
        $this->outputGenerator = $outputGenerator;
    }

    public function generateFile(array $content, string $chunkPath, array $options): void
    {
        $data = new ContentData($content, $options);

        $this->outputGenerator->reset();
        $this->outputGenerator->startDocument($data);

        $this->generateFileContent($data);

        $filePath = $this->fileManager->getDir() . $chunkPath;
        $this->fileManager->save($filePath, $this->outputGenerator->endDocument($data));

        unset($data);

        $this->logger->info(sprintf('Generating file: %s', $filePath));
    }

    private function generateFileContent(ContentData $data): void
    {
        $contents = [];

        foreach ($data->contents as $contentTypes) {
            foreach ($contentTypes as $contentType) {
                $contents[] = $contentType;
            }
        }

        $this->contentListElementGenerator->generateElement($this->outputGenerator, $contents);

        unset($contents);
    }
}

class_alias(ExportFileGenerator::class, 'EzSystems\EzRecommendationClient\File\ExportFileGenerator');
