<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Exception;

class InvalidRelationException extends InvalidArgumentException
{
    public function __construct($message, ?Exception $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}

class_alias(InvalidRelationException::class, 'EzSystems\EzRecommendationClient\Exception\InvalidRelationException');
