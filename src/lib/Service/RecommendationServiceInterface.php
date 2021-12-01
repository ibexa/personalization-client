<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Service;

use Ibexa\PersonalizationClient\SPI\RecommendationRequest;
use Ibexa\PersonalizationClient\Value\RecommendationItem;
use Psr\Http\Message\ResponseInterface;

interface RecommendationServiceInterface
{
    public function getRecommendations(RecommendationRequest $request): ?ResponseInterface;

    public function sendDeliveryFeedback(string $outputContentType): void;

    /**
     * @return \Ibexa\PersonalizationClient\Value\RecommendationItem[]
     */
    public function getRecommendationItems(array $recommendationItems): array;
}

class_alias(RecommendationServiceInterface::class, 'EzSystems\EzRecommendationClient\Service\RecommendationServiceInterface');
