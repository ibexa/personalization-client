<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PersonalizationClient\Event\Subscriber;

use Ibexa\Contracts\Core\Repository\Values\Content\ContentInfo;
use Ibexa\Core\Repository\Values\Content\Content;
use Ibexa\Core\Repository\Values\Content\Location;
use Ibexa\Core\Repository\Values\Content\VersionInfo;
use Ibexa\PersonalizationClient\Service\EventNotificationService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class AbstractCoreEventSubscriberTest extends TestCase
{
    /** @var \PHPUnit\Framework\MockObject\MockObject|\Ibexa\PersonalizationClient\Service\EventNotificationService */
    protected $notificationServiceMock;

    /** @var \Ibexa\Contracts\Core\Repository\Values\Content\ContentInfo */
    protected $contentInfo;

    /** @var \Ibexa\Core\Repository\Values\Content\Location */
    protected $location;

    /** @var \Ibexa\Core\Repository\Values\Content\Content */
    protected $content;

    public function setUp(): void
    {
        $this->notificationServiceMock = $this->createMock(EventNotificationService::class);
        $this->contentInfo = new ContentInfo([
            'id' => 1,
            'contentTypeId' => 2,
        ]);
        $this->location = new Location([
            'id' => 1,
            'path' => ['1', '5'],
            'contentInfo' => $this->contentInfo,
        ]);
        $this->content = new Content([
            'versionInfo' => new VersionInfo([
                'contentInfo' => $this->contentInfo,
            ]),
        ]);
    }

    /**
     * @dataProvider subscribedEventsDataProvider
     */
    public function testHasSubscribedEvent(string $event)
    {
        $this->assertArrayHasKey($event, $this->getEventSubscriber()::getSubscribedEvents());
    }

    abstract public function getEventSubscriber(): EventSubscriberInterface;

    abstract public function subscribedEventsDataProvider(): array;
}

class_alias(AbstractCoreEventSubscriberTest::class, 'EzSystems\EzRecommendationClient\Tests\Event\Subscriber\AbstractCoreEventSubscriberTest');
