<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Service;

use Ibexa\PersonalizationClient\Helper\SessionHelper;
use Ibexa\PersonalizationClient\Helper\UserHelper;
use Ibexa\PersonalizationClient\Value\Session;

final class UserService implements UserServiceInterface
{
    /** @var \EzSystems\EzRecommendationClient\Helper\UserHelper */
    private $userHelper;

    /** @var \EzSystems\EzRecommendationClient\Helper\SessionHelper */
    private $sessionHelper;

    public function __construct(UserHelper $userHelper, SessionHelper $sessionHelper)
    {
        $this->userHelper = $userHelper;
        $this->sessionHelper = $sessionHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserIdentifier(): string
    {
        $userIdentifier = $this->userHelper->getCurrentUser();

        if (!$userIdentifier) {
            $userIdentifier = $this->sessionHelper->getAnonymousSessionId(Session::RECOMMENDATION_SESSION_KEY);
        }

        return (string)$userIdentifier;
    }
}

class_alias(UserService::class, 'EzSystems\EzRecommendationClient\Service\UserService');
