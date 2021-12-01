<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PersonalizationClient\Event\Subscriber;

use Ibexa\Contracts\Core\Repository\Events\ObjectState\SetContentStateEvent;
use Ibexa\PersonalizationClient\Event\Subscriber\ObjectStateEventSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ObjectStateEventSubscriberTest extends AbstractCoreEventSubscriberTest
{
    /** @var \Ibexa\PersonalizationClient\Event\Subscriber\ObjectStateEventSubscriber */
    private $objectStateEventSubscriber;

    public function setUp(): void
    {
        parent::setUp();

        $this->objectStateEventSubscriber = new ObjectStateEventSubscriber($this->notificationServiceMock);
    }

    public function testCreateInstanceOfObjectStateEventSubscriber()
    {
        $this->assertInstanceOf(ObjectStateEventSubscriber::class, $this->objectStateEventSubscriber);
    }

    public function getEventSubscriber(): EventSubscriberInterface
    {
        return $this->objectStateEventSubscriber;
    }

    public function subscribedEventsDataProvider(): array
    {
        return [
            [SetContentStateEvent::class],
        ];
    }

    public function testCallOnSetContentStateMethod()
    {
        $event = $this->createMock(SetContentStateEvent::class);
        $event
            ->expects($this->once())
            ->method('getContentInfo')
            ->willReturn($this->contentInfo);

        $this->objectStateEventSubscriber->onSetContentState($event);
    }
}

class_alias(ObjectStateEventSubscriberTest::class, 'EzSystems\EzRecommendationClient\Tests\Event\Subscriber\ObjectStateEventSubscriberTest');
