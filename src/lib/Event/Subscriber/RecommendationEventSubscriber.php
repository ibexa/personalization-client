<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Event\Subscriber;

use Ibexa\Core\MVC\Symfony\Locale\LocaleConverterInterface;
use Ibexa\PersonalizationClient\Event\RecommendationResponseEvent;
use Ibexa\PersonalizationClient\Helper\ContentTypeHelper;
use Ibexa\PersonalizationClient\Helper\LocationHelper;
use Ibexa\PersonalizationClient\Request\BasicRecommendationRequest as Request;
use Ibexa\PersonalizationClient\Service\RecommendationServiceInterface;
use Ibexa\PersonalizationClient\SPI\RecommendationRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;

final class RecommendationEventSubscriber implements EventSubscriberInterface
{
    private const LOCALE_REQUEST_KEY = '_locale';
    private const DEFAULT_LOCALE = 'eng-GB';

    /** @var \Ibexa\PersonalizationClient\Service\RecommendationServiceInterface */
    private $recommendationService;

    /** @var \Ibexa\Core\MVC\Symfony\Locale\LocaleConverterInterface */
    private $localeConverter;

    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    /** @var \Ibexa\PersonalizationClient\Helper\ContentTypeHelper */
    private $contentTypeHelper;

    /** @var \Ibexa\PersonalizationClient\Helper\LocationHelper */
    private $locationHelper;

    public function __construct(
        RecommendationServiceInterface $recommendationService,
        LocaleConverterInterface $localeConverter,
        LoggerInterface $logger,
        ContentTypeHelper $contentTypeHelper,
        LocationHelper $locationHelper
    ) {
        $this->recommendationService = $recommendationService;
        $this->localeConverter = $localeConverter;
        $this->logger = $logger;
        $this->contentTypeHelper = $contentTypeHelper;
        $this->locationHelper = $locationHelper;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            RecommendationResponseEvent::class => ['onRecommendationResponse', 10],
        ];
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function onRecommendationResponse(RecommendationResponseEvent $event): void
    {
        $recommendationRequest = $this->getRecommendationRequest($event->getParameterBag());

        $response = $this->recommendationService->getRecommendations($recommendationRequest);

        if (!$response) {
            return;
        }

        $event->setRecommendationItems($this->extractRecommendationItems($response));
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    private function getRecommendationRequest(ParameterBag $parameterBag): RecommendationRequest
    {
        $contextItems = (int) $parameterBag->get(Request::CONTEXT_ITEMS_KEY, 0);

        return new Request([
            RecommendationRequest::SCENARIO => $parameterBag->get(RecommendationRequest::SCENARIO, ''),
            Request::LIMIT_KEY => $parameterBag->get(Request::LIMIT_KEY, 3),
            Request::CONTEXT_ITEMS_KEY => $contextItems,
            Request::CONTENT_TYPE_KEY => $this->contentTypeHelper->getContentTypeId($this->contentTypeHelper->getContentTypeIdentifier($contextItems)),
            Request::OUTPUT_TYPE_ID_KEY => $this->contentTypeHelper->getContentTypeId($parameterBag->get(Request::OUTPUT_TYPE_ID_KEY, '')),
            Request::CATEGORY_PATH_KEY => $this->locationHelper->getParentLocationPathString($contextItems),
            Request::LANGUAGE_KEY => $this->getRequestLanguage($parameterBag->get(self::LOCALE_REQUEST_KEY)),
            Request::ATTRIBUTES_KEY => $parameterBag->get(Request::ATTRIBUTES_KEY, []),
            Request::FILTERS_KEY => $parameterBag->get(Request::FILTERS_KEY, []),
        ]);
    }

    private function getRequestLanguage(?string $locale): string
    {
        return $this->localeConverter->convertToEz($locale) ?? self::DEFAULT_LOCALE;
    }

    private function extractRecommendationItems(ResponseInterface $response): array
    {
        if ($response->getStatusCode() !== Response::HTTP_OK) {
            $this->logger->warning('RecommendationApi: StatusCode: ' . $response->getStatusCode() . ' Message: ' . $response->getReasonPhrase());
        }

        $recommendations = $response->getBody()->getContents();

        $recommendationItems = json_decode($recommendations, true);

        return $this->recommendationService->getRecommendationItems($recommendationItems['recommendationItems']);
    }
}

class_alias(RecommendationEventSubscriber::class, 'EzSystems\EzRecommendationClient\Event\Subscriber\RecommendationEventSubscriber');
