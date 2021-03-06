<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Value;

use Ibexa\PersonalizationClient\SPI\Notification;

class ExportNotification extends Notification
{
    public const TRANSACTION_KEY = 'transaction';
    public const END_POINT_URI_KEY = 'webHook';

    /** @var string */
    public $transaction;

    /** @var string */
    public $endPointUri;
}

class_alias(ExportNotification::class, 'EzSystems\EzRecommendationClient\Value\ExportNotification');
