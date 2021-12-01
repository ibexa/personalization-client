<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Http;

use Ibexa\PersonalizationClient\Factory\RequestFactoryInterface;
use Ibexa\PersonalizationClient\Factory\TokenFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class FakeHttpEnvironment implements HttpEnvironmentInterface
{
    /** @var \Ibexa\PersonalizationClient\Factory\RequestFactoryInterface */
    private $requestFactory;

    /** @var \Symfony\Component\HttpFoundation\RequestStack */
    private $requestStack;

    /** @var \Ibexa\PersonalizationClient\Factory\TokenFactoryInterface */
    private $tokenFactory;

    /** @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface */
    private $tokenStorage;

    public function __construct(
        RequestFactoryInterface $requestFactory,
        RequestStack $requestStack,
        TokenFactoryInterface $tokenFactory,
        TokenStorageInterface $tokenStorage
    ) {
        $this->requestFactory = $requestFactory;
        $this->requestStack = $requestStack;
        $this->tokenFactory = $tokenFactory;
        $this->tokenStorage = $tokenStorage;
    }

    public function prepare(): void
    {
        $this->requestStack->push($this->requestFactory->createRequest());
        $this->tokenStorage->setToken($this->tokenFactory->createAnonymousToken());
    }
}

class_alias(FakeHttpEnvironment::class, 'EzSystems\EzRecommendationClient\Http\FakeHttpEnvironment');
