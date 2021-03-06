<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PersonalizationClient\Event\Subscriber;

use Ibexa\Contracts\Core\Repository\Events\Content\CopyContentEvent;
use Ibexa\Contracts\Core\Repository\Events\Content\DeleteContentEvent;
use Ibexa\Contracts\Core\Repository\Events\Content\HideContentEvent;
use Ibexa\Contracts\Core\Repository\Events\Content\PublishVersionEvent;
use Ibexa\Contracts\Core\Repository\Events\Content\RevealContentEvent;
use Ibexa\Contracts\Core\Repository\Events\Content\UpdateContentMetadataEvent;
use Ibexa\PersonalizationClient\Event\Subscriber\ContentEventSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContentEventSubscriberTest extends AbstractCoreEventSubscriberTest
{
    /** @var \PHPUnit\Framework\MockObject\MockObject|\Ibexa\PersonalizationClient\Event\Subscriber\ContentEventSubscriber */
    private $contentEventSubscriber;

    public function setUp(): void
    {
        parent::setUp();

        $this->contentEventSubscriber = new ContentEventSubscriber($this->notificationServiceMock);
    }

    public function testCreateInstanceOfContentEventSubscriber()
    {
        $this->assertInstanceOf(ContentEventSubscriber::class, $this->contentEventSubscriber);
    }

    public function getEventSubscriber(): EventSubscriberInterface
    {
        return $this->contentEventSubscriber;
    }

    public function subscribedEventsDataProvider(): array
    {
        return [
            [DeleteContentEvent::class],
            [HideContentEvent::class],
            [RevealContentEvent::class],
            [UpdateContentMetadataEvent::class],
            [CopyContentEvent::class],
            [PublishVersionEvent::class],
        ];
    }

    public function testCallOnDeleteContentMethod()
    {
        $event = $this->createMock(DeleteContentEvent::class);
        $event
            ->expects($this->once())
            ->method('getContentInfo')
            ->willReturn($this->contentInfo);

        $this->contentEventSubscriber->onDeleteContent($event);
    }

    public function testCallOnHideContentMethod()
    {
        $event = $this->createMock(HideContentEvent::class);
        $event
            ->expects($this->once())
            ->method('getContentInfo')
            ->willReturn($this->contentInfo);

        $this->contentEventSubscriber->onHideContent($event);
    }

    public function testCallOnRevealContentMethod()
    {
        $event = $this->createMock(RevealContentEvent::class);
        $event
            ->expects($this->once())
            ->method('getContentInfo')
            ->willReturn($this->contentInfo);

        $this->contentEventSubscriber->onRevealContent($event);
    }

    public function testCallOnUpdateContentMetadataMethod()
    {
        $event = $this->createMock(UpdateContentMetadataEvent::class);
        $event
            ->expects($this->once())
            ->method('getContent')
            ->willReturn($this->content);

        $this->contentEventSubscriber->onUpdateContentMetadata($event);
    }

    public function testCallOnCopyContentMethod()
    {
        $event = $this->createMock(CopyContentEvent::class);
        $event
            ->expects($this->once())
            ->method('getContent')
            ->willReturn($this->content);

        $this->contentEventSubscriber->onCopyContent($event);
    }

    public function testCallOnPublishVersionMethod()
    {
        $event = $this->createMock(PublishVersionEvent::class);
        $event
            ->expects($this->once())
            ->method('getContent')
            ->willReturn($this->content);

        $this->contentEventSubscriber->onPublishVersion($event);
    }
}

class_alias(ContentEventSubscriberTest::class, 'EzSystems\EzRecommendationClient\Tests\Event\Subscriber\ContentEventSubscriberTest');
