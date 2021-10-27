<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Value;

/**
 * This class holds ContentDataVisitor structure used by Recommendation engine.
 */
class ContentData
{
    /** @var array */
    public $contents;

    /** @var array */
    public $options;

    public function __construct(array $contents, array $options = [])
    {
        $this->contents = $contents;
        $this->options = $options;
    }
}

class_alias(ContentData::class, 'EzSystems\EzRecommendationClient\Value\ContentData');
