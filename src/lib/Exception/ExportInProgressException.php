<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Exception;

class ExportInProgressException extends ExportException
{
}

class_alias(ExportInProgressException::class, 'EzSystems\EzRecommendationClient\Exception\ExportInProgressException');
