<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\SPI;

abstract class Notification
{
    public const EVENTS_KEY = 'events';
    public const CUSTOMER_ID_KEY = 'customerId';
    public const LICENSE_KEY = 'licenseKey';

    /** @var array */
    public $events;

    /** @var int */
    public $customerId;

    /** @var string */
    public $licenseKey;
}

class_alias(Notification::class, 'EzSystems\EzRecommendationClient\SPI\Notification');
