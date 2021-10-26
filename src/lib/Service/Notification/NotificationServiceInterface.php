<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Service\Notification;

use Ibexa\PersonalizationClient\SPI\Notification;
use Psr\Http\Message\ResponseInterface;

interface NotificationServiceInterface
{
    public function send(Notification $notification, string $action): ?ResponseInterface;
}

class_alias(NotificationServiceInterface::class, 'EzSystems\EzRecommendationClient\Service\Notification\NotificationServiceInterface');
