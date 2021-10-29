<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Exception;

abstract class ExportException extends \Exception implements EzRecommendationException
{
}

class_alias(ExportException::class, 'EzSystems\EzRecommendationClient\Exception\ExportException');
