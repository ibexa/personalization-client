<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Factory;

use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

interface TokenFactoryInterface
{
    public function createAnonymousToken(?string $secret = null, ?string $user = null, array $roles = []): AnonymousToken;
}

class_alias(TokenFactoryInterface::class, 'EzSystems\EzRecommendationClient\Factory\TokenFactoryInterface');
