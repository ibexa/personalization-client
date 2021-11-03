<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PersonalizationClient\Service;

use eZ\Publish\API\Repository\Values\Content\ContentInfo;
use Ibexa\PersonalizationClient\API\Notifier;
use Ibexa\PersonalizationClient\Config\ExportCredentialsResolver;
use Ibexa\PersonalizationClient\Config\EzRecommendationClientCredentialsResolver;
use Ibexa\PersonalizationClient\Helper\ContentHelper;
use Ibexa\PersonalizationClient\Helper\ContentTypeHelper;
use Ibexa\PersonalizationClient\Service\EventNotificationService;
use Ibexa\PersonalizationClient\Value\Config\ExportCredentials;
use Ibexa\PersonalizationClient\Value\Config\EzRecommendationClientCredentials;
use Ibexa\PersonalizationClient\Value\EventNotification;

class EventNotificationServiceTest extends NotificationServiceTest
{
    /** @var \EzSystems\EzRecommendationClient\Service\EventNotificationService */
    private $notificationService;

    /** @var \EzSystems\EzRecommendationClient\Config\EzRecommendationClientCredentialsResolver|\PHPUnit\Framework\MockObject\MockObject */
    private $credentialsResolverMock;

    /** @var \EzSystems\EzRecommendationClient\Config\ExportCredentialsResolver|\PHPUnit\Framework\MockObject\MockObject */
    private $exportCredentialsMock;

    /** @var \EzSystems\EzRecommendationClient\Helper\ContentHelper|\PHPUnit\Framework\MockObject\MockObject */
    private $contentHelperMock;

    /** @var \EzSystems\EzRecommendationClient\Helper\ContentTypeHelper|\PHPUnit\Framework\MockObject\MockObject */
    private $contentTypeHelperMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->credentialsResolverMock = $this->createMock(EzRecommendationClientCredentialsResolver::class);
        $this->exportCredentialsMock = $this->createMock(ExportCredentialsResolver::class);
        $this->contentHelperMock = $this->createMock(ContentHelper::class);
        $this->contentTypeHelperMock = $this->createMock(ContentTypeHelper::class);
        $this->notificationService = new EventNotificationService(
            $this->clientMock,
            $this->loggerMock,
            $this->credentialsResolverMock,
            $this->exportCredentialsMock,
            $this->contentHelperMock,
            $this->contentTypeHelperMock
        );
    }

    public function testCreateInstanceOfEventNotificationService()
    {
        $this->assertInstanceOf(EventNotificationService::class, $this->notificationService);
    }

    public function testCreateEventNotification()
    {
        $this->assertInstanceOf(
            EventNotification::class,
            $this->notificationService->createNotification($this->basicNotificationOptions)
        );
    }

    public function testSendNotification()
    {
        $this->credentialsResolverMock
            ->expects($this->once())
            ->method('getCredentials')
            ->willReturn(
                EzRecommendationClientCredentials::fromArray(
                    [
                        'customerId' => 12345,
                        'licenseKey' => '12345-12345-12345-12345',
                    ]
                )
            );

        $this->exportCredentialsMock
            ->method('getCredentials')
            ->willReturn(
                ExportCredentials::fromArray(
                    [
                        ExportCredentials::METHOD_KEY => 'basic',
                        ExportCredentials::LOGIN_KEY => '12345',
                        ExportCredentials::PASSWORD_KEY => '12345-12345-12345-12345',
                    ]
                )
            );

        $this->clientMock
            ->method('__call')
            ->with(Notifier::API_NAME, [])
            ->willReturn($this->createMock(Notifier::class));

        $this->notificationService->sendNotification(
            'onHideLocation',
            EventNotification::ACTION_UPDATE,
            new ContentInfo([])
        );
    }
}

class_alias(EventNotificationServiceTest::class, 'EzSystems\EzRecommendationClient\Tests\Service\EventNotificationServiceTest');
