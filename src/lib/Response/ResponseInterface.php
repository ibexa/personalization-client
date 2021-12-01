<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Response;

use Ibexa\Contracts\Rest\Output\Generator;

interface ResponseInterface
{
    /**
     * @param $data
     */
    public function render(Generator $generator, $data): Generator;
}

class_alias(ResponseInterface::class, 'EzSystems\EzRecommendationClient\Response\ResponseInterface');
