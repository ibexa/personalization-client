<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Event\Listener;

use GuzzleHttp\Exception\RequestException;
use Ibexa\Contracts\Core\Repository\UserService as UserServiceInterface;
use Ibexa\Core\MVC\ConfigResolverInterface;
use Ibexa\Core\MVC\Symfony\Security\UserInterface;
use Ibexa\PersonalizationClient\Client\EzRecommendationClientInterface;
use Ibexa\PersonalizationClient\Value\Parameters;
use Ibexa\PersonalizationClient\Value\Session as RecommendationSession;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Sends notification to Recommendation servers when user is logged in.
 */
final class LoginListener
{
    /** @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface */
    private $authorizationChecker;

    /** @var \Symfony\Component\HttpFoundation\Session\SessionInterface */
    private $session;

    /** @var \Ibexa\PersonalizationClient\Client\EzRecommendationClientInterface */
    private $client;

    /** @var \Ibexa\Contracts\Core\Repository\UserService */
    private $userService;

    /** @var \Ibexa\Core\MVC\ConfigResolverInterface */
    private $configResolver;

    /** @var \Psr\Log\LoggerInterface|null */
    private $logger;

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        SessionInterface $session,
        EzRecommendationClientInterface $client,
        UserServiceInterface $userService,
        ConfigResolverInterface $configResolver,
        LoggerInterface $logger
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->session = $session;
        $this->client = $client;
        $this->userService = $userService;
        $this->configResolver = $configResolver;
        $this->logger = $logger;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        if ($event->getRequest()->get('is_rest_request')) {
            return;
        }

        if (!$this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY') // user has just logged in
            || !$this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED') // user has logged in using remember_me cookie
        ) {
            return;
        }

        if (!$event->getRequest()->cookies->has(RecommendationSession::RECOMMENDATION_SESSION_KEY)) {
            if (!$this->session->isStarted()) {
                $this->session->start();
            }
            $event->getRequest()->cookies->set(RecommendationSession::RECOMMENDATION_SESSION_KEY, $this->session->getId());
        }

        $notificationUri = sprintf(
            $this->getNotificationEndpoint() . '%s/%s/%s',
            'login',
            $event->getRequest()->cookies->get(RecommendationSession::RECOMMENDATION_SESSION_KEY),
            $this->getUser($event->getAuthenticationToken())
        );

        $this->logger->debug(sprintf('Send login event notification to Recommendation: %s', $notificationUri));

        try {
            /** @var \Psr\Http\Message\ResponseInterface $response */
            $response = $this->client->getHttpClient()->get($notificationUri);

            $this->logger->debug(sprintf('Got %s from Recommendation login event notification', $response->getStatusCode()));
        } catch (RequestException $e) {
            $this->logger->error(sprintf('Recommendation login event notification error: %s', $e->getMessage()));
        }
    }

    /**
     * Returns notification API end-point.
     */
    private function getNotificationEndpoint(): string
    {
        $customerId = $this->configResolver->getParameter(
            'authentication.customer_id',
            Parameters::NAMESPACE
        );
        $trackingEndPoint = $this->configResolver->getParameter(
            Parameters::API_SCOPE . '.event_tracking.endpoint',
            Parameters::NAMESPACE
        );

        return sprintf('%s/api/%s/', $trackingEndPoint, $customerId);
    }

    /**
     * Returns current username or ApiUser id.
     */
    private function getUser(TokenInterface $authenticationToken): string
    {
        $user = $authenticationToken->getUser();
        if ($user instanceof UserInterface) {
            return (string) $user->getAPIUser()->id;
        }

        return $authenticationToken->getUserIdentifier();
    }
}

class_alias(LoginListener::class, 'EzSystems\EzRecommendationClient\Event\Listener\LoginListener');
