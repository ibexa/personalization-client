<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PersonalizationClient\Event\Subscriber;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\LocationService;
use Ibexa\PersonalizationClient\Helper\ContentHelper;
use Ibexa\PersonalizationClient\Helper\LocationHelper;

abstract class AbstractRepositoryEventSubscriberTest extends AbstractCoreEventSubscriberTest
{
    /** @var \PHPUnit\Framework\MockObject\MockObject|\eZ\Publish\API\Repository\ContentService */
    protected $contentServiceMock;

    /** @var \PHPUnit\Framework\MockObject\MockObject|\eZ\Publish\API\Repository\LocationService */
    protected $locationServiceMock;

    /** @var \PHPUnit\Framework\MockObject\MockObject|\EzSystems\EzRecommendationClient\Helper\LocationHelper */
    protected $locationHelperMock;

    /** @var \PHPUnit\Framework\MockObject\MockObject|\EzSystems\EzRecommendationClient\Helper\ContentHelper */
    protected $contentHelperMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->contentServiceMock = $this->createMock(ContentService::class);
        $this->locationServiceMock = $this->createMock(LocationService::class);
        $this->locationHelperMock = $this->createMock(LocationHelper::class);
        $this->contentHelperMock = $this->createMock(ContentHelper::class);
    }
}

class_alias(AbstractRepositoryEventSubscriberTest::class, 'EzSystems\EzRecommendationClient\Tests\Event\Subscriber\AbstractRepositoryEventSubscriberTest');
