<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Response;

use Ibexa\PersonalizationClient\Generator\ContentListElementGenerator;

abstract class Response implements ResponseInterface
{
    /** @var \Ibexa\PersonalizationClient\Generator\ContentListElementGenerator */
    public $contentListElementGenerator;

    public function __construct(ContentListElementGenerator $contentListElementGenerator)
    {
        $this->contentListElementGenerator = $contentListElementGenerator;
    }
}

class_alias(Response::class, 'EzSystems\EzRecommendationClient\Response\Response');
