<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PersonalizationClient\Service;

use Ibexa\PersonalizationClient\Client\EzRecommendationClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

abstract class NotificationServiceTest extends TestCase
{
    /** @var \EzSystems\EzRecommendationClient\Client\EzRecommendationClientInterface|\PHPUnit\Framework\MockObject\MockObject */
    protected $clientMock;

    /** @var \Psr\Log\LoggerInterface|\PHPUnit\Framework\MockObject\MockObject */
    protected $loggerMock;

    /** @var array */
    protected $basicNotificationOptions;

    public function setUp(): void
    {
        $this->clientMock = $this->createMock(EzRecommendationClientInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->basicNotificationOptions = [
            'events' => ['event1', 'event2', 'event3'],
            'customerId' => 12345,
            'licenseKey' => '12345-12345-12345-12345',
        ];
    }
}

class_alias(NotificationServiceTest::class, 'EzSystems\EzRecommendationClient\Tests\Service\NotificationServiceTest');
