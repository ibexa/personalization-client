<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Service;

use Ibexa\PersonalizationClient\Value\Export\Parameters;
use Symfony\Component\Console\Output\OutputInterface;

interface ExportServiceInterface
{
    public function runExport(Parameters $parameters, OutputInterface $output): void;
}

class_alias(ExportServiceInterface::class, 'EzSystems\EzRecommendationClient\Service\ExportServiceInterface');
