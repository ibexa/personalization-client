<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Exception;

class BadAPICallException extends APIException
{
    public function __construct($name, ?\Throwable $previous = null)
    {
        $message = sprintf('Given API class %s is not callable', $name);

        parent::__construct($message, 0, $previous);
    }
}

class_alias(BadAPICallException::class, 'EzSystems\EzRecommendationClient\Exception\BadAPICallException');
