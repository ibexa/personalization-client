<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Config;

use Ibexa\PersonalizationClient\Value\Config\Credentials;

interface CredentialsResolverInterface
{
    /**
     * Returns object with credentials data.
     */
    public function getCredentials(?string $siteAccess = null): ?Credentials;

    public function hasCredentials(?string $siteAccess = null): bool;
}

class_alias(CredentialsResolverInterface::class, 'EzSystems\EzRecommendationClient\Config\CredentialsResolverInterface');
