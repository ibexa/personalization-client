<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\Templating\Twig\Functions;

use Ibexa\Core\MVC\ConfigResolverInterface;
use Ibexa\Core\MVC\Symfony\Locale\LocaleConverterInterface;
use Ibexa\PersonalizationClient\Helper\ContentTypeHelper;
use Ibexa\PersonalizationClient\Service\UserServiceInterface;
use Ibexa\PersonalizationClient\Value\Parameters;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment as TwigEnvironment;
use Twig\Extension\RuntimeExtensionInterface;

final class UserTracking extends AbstractFunction implements RuntimeExtensionInterface
{
    /** @var \Ibexa\Core\MVC\ConfigResolverInterface */
    private $configResolver;

    /** @var \Ibexa\Core\MVC\Symfony\Locale\LocaleConverterInterface */
    private $localeConverter;

    /** @var \Ibexa\PersonalizationClient\Service\UserServiceInterface */
    private $userService;

    /** @var \Ibexa\PersonalizationClient\Helper\ContentTypeHelper */
    private $contentTypeHelper;

    /** @var \Symfony\Component\HttpFoundation\RequestStack */
    private $requestStack;

    public function __construct(
        ConfigResolverInterface $configResolver,
        LocaleConverterInterface $localeConverter,
        UserServiceInterface $userService,
        ContentTypeHelper $contentTypeHelper,
        RequestStack $requestStack,
        TwigEnvironment $twig
    ) {
        parent::__construct($twig);

        $this->configResolver = $configResolver;
        $this->contentTypeHelper = $contentTypeHelper;
        $this->localeConverter = $localeConverter;
        $this->requestStack = $requestStack;
        $this->userService = $userService;
    }

    /**
     * Renders simple tracking snippet code.
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function trackUser(int $contentId): string
    {
        $includedContentTypes = $this->configResolver->getParameter('included_content_types', Parameters::NAMESPACE);
        $customerId = $this->configResolver->getParameter('authentication.customer_id', Parameters::NAMESPACE);

        if (!\in_array($this->contentTypeHelper->getContentTypeIdentifier($contentId), $includedContentTypes)) {
            return '';
        }

        return $this->twig->render(
            '@EzRecommendationClient/track_user.html.twig',
            [
                'contentId' => $contentId,
                'contentTypeId' => $this->contentTypeHelper->getContentTypeId(
                    $this->contentTypeHelper->getContentTypeIdentifier($contentId)
                ),
                'language' => $this->localeConverter->convertToEz($this->requestStack->getCurrentRequest()->get('_locale')),
                'userId' => $this->userService->getUserIdentifier(),
                'customerId' => $customerId,
                'consumeTimeout' => $this->getConsumeTimeout(),
                'trackingScriptUrl' => $this->configResolver->getParameter(
                    Parameters::API_SCOPE . '.event_tracking.script_url',
                    Parameters::NAMESPACE
                ),
            ]
        );
    }

    private function getConsumeTimeout(): int
    {
        $consumeTimout = (int)$this->configResolver->getParameter(
            Parameters::API_SCOPE . '.recommendation.consume_timeout',
            Parameters::NAMESPACE
        );

        return $consumeTimout * 1000;
    }
}

class_alias(UserTracking::class, 'EzSystems\EzRecommendationClientBundle\Templating\Twig\Functions\UserTracking');
