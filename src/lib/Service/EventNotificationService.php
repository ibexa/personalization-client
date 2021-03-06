<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Service;

use Ibexa\Contracts\Core\Repository\Values\Content\ContentInfo;
use Ibexa\PersonalizationClient\Client\EzRecommendationClientInterface;
use Ibexa\PersonalizationClient\Config\CredentialsResolverInterface;
use Ibexa\PersonalizationClient\Helper\ContentHelper;
use Ibexa\PersonalizationClient\Helper\ContentTypeHelper;
use Ibexa\PersonalizationClient\Request\EventNotifierRequest;
use Ibexa\PersonalizationClient\SPI\Notification;
use Ibexa\PersonalizationClient\Value\Config\ExportCredentials;
use Ibexa\PersonalizationClient\Value\EventNotification;
use Psr\Log\LoggerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class EventNotificationService extends NotificationService
{
    /** @var \Ibexa\PersonalizationClient\Config\CredentialsResolverInterface */
    private $clientCredentials;

    /** @var \Ibexa\PersonalizationClient\Config\CredentialsResolverInterface */
    private $exportCredentials;

    /** @var \Ibexa\PersonalizationClient\Helper\ContentHelper */
    private $contentHelper;

    /** @var \Ibexa\PersonalizationClient\Helper\ContentTypeHelper */
    private $contentTypeHelper;

    public function __construct(
        EzRecommendationClientInterface $client,
        LoggerInterface $logger,
        CredentialsResolverInterface $clientCredentials,
        CredentialsResolverInterface $exportCredentials,
        ContentHelper $contentHelper,
        ContentTypeHelper $contentTypeHelper
    ) {
        parent::__construct($client, $logger);

        $this->clientCredentials = $clientCredentials;
        $this->exportCredentials = $exportCredentials;
        $this->contentHelper = $contentHelper;
        $this->contentTypeHelper = $contentTypeHelper;
    }

    /**
     * @throws \Exception
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    public function sendNotification(string $method, string $action, ContentInfo $contentInfo): void
    {
        $credentials = $this->clientCredentials->getCredentials();

        if (!$credentials || $this->contentTypeHelper->isContentTypeExcluded($contentInfo)) {
            return;
        }

        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $notificationOptions = $resolver->resolve([
            'events' => $this->generateNotificationEvents($action, $contentInfo, $this->exportCredentials->getCredentials()),
            'licenseKey' => $credentials->getLicenseKey(),
            'customerId' => $credentials->getCustomerId(),
        ]);

        $this->send(
            $this->createNotification($notificationOptions),
            $method
        );
    }

    /**
     * {@inheritdoc}
     */
    public function createNotification(array $options): Notification
    {
        $notification = new EventNotification();
        $notification->events = $options['events'];
        $notification->customerId = $options['customerId'];
        $notification->licenseKey = $options['licenseKey'];

        return $notification;
    }

    /**
     * @param \Ibexa\Contracts\Core\Repository\Values\Content\ContentInfo $content
     *
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\NotFoundException
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\UnauthorizedException
     */
    private function generateNotificationEvents(
        string $action,
        ContentInfo $contentInfo,
        ExportCredentials $exportCredentials
    ): array {
        $events = [];

        foreach ($this->contentHelper->getLanguageCodes($contentInfo) as $lang) {
            $event = new EventNotifierRequest([
                EventNotifierRequest::ACTION_KEY => $action,
                EventNotifierRequest::FORMAT_KEY => 'EZ',
                EventNotifierRequest::URI_KEY => $this->contentHelper->getContentUri($contentInfo, $lang),
                EventNotifierRequest::ITEM_ID_KEY => $contentInfo->id,
                EventNotifierRequest::CONTENT_TYPE_ID_KEY => $contentInfo->contentTypeId,
                EventNotifierRequest::LANG_KEY => $lang ?? null,
                EventNotifierRequest::CREDENTIALS_KEY => [
                    'login' => $exportCredentials->getLogin(),
                    'password' => $exportCredentials->getPassword(),
                ],
            ]);

            $events[] = $event->getRequestAttributes();
        }

        return $events;
    }
}

class_alias(EventNotificationService::class, 'EzSystems\EzRecommendationClient\Service\EventNotificationService');
