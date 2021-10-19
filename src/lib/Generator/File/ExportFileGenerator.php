<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Generator\File;

use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\PersonalizationClient\File\FileManagerInterface;
use Ibexa\PersonalizationClient\Generator\ItemList\ItemListOutputGeneratorInterface;
use Ibexa\PersonalizationClient\Value\Export\FileSettings;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class ExportFileGenerator implements ExportFileGeneratorInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    private FileManagerInterface $fileManager;

    private ItemListOutputGeneratorInterface $itemListElementGenerator;

    private Generator $outputGenerator;

    public function __construct(
        FileManagerInterface $fileManager,
        ItemListOutputGeneratorInterface $itemListElementGenerator,
        Generator $outputGenerator,
        ?LoggerInterface $logger = null
    ) {
        $this->fileManager = $fileManager;
        $this->itemListElementGenerator = $itemListElementGenerator;
        $this->outputGenerator = $outputGenerator;
        $this->logger = $logger ?? new NullLogger();
    }

    public function generate(FileSettings $fileSettings): void
    {
        $output = $this->itemListElementGenerator->getOutput($this->outputGenerator, $fileSettings->getItemList());
        $filePath = $this->fileManager->getDir() . $fileSettings->getChunkPath();
        $this->fileManager->save($filePath, $output);

        $this->logger->info(sprintf('Generating file: %s', $filePath));
    }
}
