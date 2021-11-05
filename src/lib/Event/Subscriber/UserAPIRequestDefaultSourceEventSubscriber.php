<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Event\Subscriber;

use eZ\Publish\Core\MVC\ConfigResolverInterface;
use Ibexa\PersonalizationClient\Event\UpdateUserAPIEvent;
use Ibexa\PersonalizationClient\Request\UserMetadataRequest;
use Ibexa\PersonalizationClient\Value\Parameters;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class UserAPIRequestDefaultSourceEventSubscriber implements EventSubscriberInterface
{
    /** @var \eZ\Publish\Core\MVC\ConfigResolverInterface */
    private $configResolver;

    public function __construct(ConfigResolverInterface $configResolver)
    {
        $this->configResolver = $configResolver;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            UpdateUserAPIEvent::class => ['onRecommendationUpdateUser', 255],
        ];
    }

    public function onRecommendationUpdateUser(UpdateUserAPIEvent $userAPIEvent): void
    {
        if ($userAPIEvent->getUserAPIRequest()) {
            return;
        }

        $userAPIEvent->setUserAPIRequest(new UserMetadataRequest([
            'source' => $this->configResolver->getParameter('user_api.default_source', Parameters::NAMESPACE),
        ]));
    }
}

class_alias(UserAPIRequestDefaultSourceEventSubscriber::class, 'EzSystems\EzRecommendationClient\Event\Subscriber\UserAPIRequestDefaultSourceEventSubscriber');
