<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient;

use Ibexa\Bundle\PersonalizationClient\DependencyInjection\Compiler\RestResponsePass;
use Ibexa\Bundle\PersonalizationClient\DependencyInjection\IbexaPersonalizationClientExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class IbexaPersonalizationClientBundle extends Bundle
{
    /** @var \EzSystems\EzRecommendationClientBundle\DependencyInjection\EzRecommendationClientExtension */
    protected $extension;

    /**
     * @return \EzSystems\EzRecommendationClientBundle\DependencyInjection\EzRecommendationClientExtension
     */
    public function getContainerExtension()
    {
        return $this->extension ?? new EzRecommendationClientExtension();
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RestResponsePass());
    }
}

class_alias(IbexaPersonalizationClientBundle::class, 'EzSystems\EzRecommendationClientBundle\EzRecommendationClientBundle');
