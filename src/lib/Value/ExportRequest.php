<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Value;

class ExportRequest extends ExportParameters
{
    /** @var string */
    public $documentRoot;

    public function getExportRequestParameters(): array
    {
        return get_object_vars($this);
    }
}

class_alias(ExportRequest::class, 'EzSystems\EzRecommendationClient\Value\ExportRequest');
