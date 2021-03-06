<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Service;

use Ibexa\PersonalizationClient\Request\ExportNotifierRequest;
use Ibexa\PersonalizationClient\SPI\Notification;
use Ibexa\PersonalizationClient\Value\Export\Parameters;
use Ibexa\PersonalizationClient\Value\ExportNotification;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ExportNotificationService extends NotificationService
{
    private const NOTIFICATION_ACTION_NAME = 'export';

    public function sendNotification(Parameters $parameters, array $urls, array $securedDirCredentials): ?ResponseInterface
    {
        $options = [
            'events' => $this->getNotificationEvents($urls, $securedDirCredentials),
            'customerId' => $parameters->getCustomerId(),
            'licenseKey' => $parameters->getLicenseKey(),
            'webHook' => $parameters->getWebHook(),
        ];

        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        return $this->send(
            $this->createNotification($options),
            self::NOTIFICATION_ACTION_NAME
        );
    }

    /**
     * {@inheritdoc}
     */
    public function createNotification(array $options): Notification
    {
        $notification = new ExportNotification();
        $notification->events = $options['events'];
        $notification->licenseKey = $options['licenseKey'];
        $notification->customerId = (int)$options['customerId'];
        $notification->transaction = date('YmdHis') . random_int(111, 999);
        $notification->endPointUri = $options['webHook'];

        return $notification;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setRequired([
            'transaction',
            'webHook',
        ])
            ->setDefaults([
                ExportNotification::TRANSACTION_KEY => null,
                ExportNotification::END_POINT_URI_KEY => null,
            ])
            ->setAllowedTypes('transaction', 'string')
            ->setAllowedTypes('webHook', 'string');
    }

    private function getNotificationEvents(array $urls, array $securedDirCredentials): array
    {
        $notifications = [];

        foreach ($urls as $contentTypeId => $languages) {
            foreach ($languages as $lang => $contentTypeInfo) {
                $notification = new ExportNotifierRequest([
                    ExportNotifierRequest::ACTION_KEY => 'FULL',
                    ExportNotifierRequest::FORMAT_KEY => 'EZ',
                    ExportNotifierRequest::CONTENT_TYPE_ID_KEY => $contentTypeId,
                    ExportNotifierRequest::CONTENT_TYPE_NAME_KEY => $contentTypeInfo['contentTypeName'],
                    ExportNotifierRequest::LANG_KEY => $lang,
                    ExportNotifierRequest::URI_KEY => $contentTypeInfo['urlList'],
                    ExportNotifierRequest::CREDENTIALS_KEY => $securedDirCredentials ?? null,
                ]);

                $notifications[] = $notification->getRequestAttributes();
            }
        }

        return $notifications;
    }
}

class_alias(ExportNotificationService::class, 'EzSystems\EzRecommendationClient\Service\ExportNotificationService');
