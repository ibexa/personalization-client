<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Helper;

use Ibexa\Contracts\Core\Repository\Values\Content\Content;
use Ibexa\Contracts\Core\Repository\Values\Content\Field;
use Ibexa\Contracts\Core\Variation\VariationHandler as ImageVariationServiceInterface;
use Ibexa\Core\MVC\ConfigResolverInterface;

final class ImageHelper
{
    /** @var \Ibexa\Contracts\Core\Variation\VariationHandler */
    private $imageVariationService;

    /** @var \Ibexa\Bundle\Core\DependencyInjection\Configuration\ConfigResolver */
    private $configResolver;

    /**
     * @param \Ibexa\Contracts\Core\Variation\VariationHandler $imageVariation
     * @param \Ibexa\Bundle\Core\DependencyInjection\Configuration\ConfigResolver $configResolver
     */
    public function __construct(
        ImageVariationServiceInterface $imageVariationService,
        ConfigResolverInterface $configResolver
    ) {
        $this->imageVariationService = $imageVariationService;
        $this->configResolver = $configResolver;
    }

    public function getImageUrl(Field $field, Content $content, ?array $options = null): string
    {
        $variations = $this->configResolver->getParameter('image_variations');
        $variation = 'original';

        if ((!empty($options['image'])) && \in_array($options['image'], array_keys($variations))) {
            $variation = $options['image'];
        }

        $uri = $this
            ->imageVariationService
            ->getVariation($field, $content->versionInfo, $variation)
            ->uri;

        if (strpos($uri, 'http://:0') !== false) {
            $uri = str_replace('http://:0', 'http://0', $uri);
        }

        return parse_url($uri, PHP_URL_PATH);
    }
}

class_alias(ImageHelper::class, 'EzSystems\EzRecommendationClient\Helper\ImageHelper');
