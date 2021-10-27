<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\Templating\Twig\Functions;

use Twig\Environment as TwigEnvironment;

abstract class AbstractFunction
{
    /** @var \Twig\Environment */
    protected $twig;

    public function __construct(TwigEnvironment $twig)
    {
        $this->twig = $twig;
    }
}

class_alias(AbstractFunction::class, 'EzSystems\EzRecommendationClientBundle\Templating\Twig\Functions\AbstractFunction');
