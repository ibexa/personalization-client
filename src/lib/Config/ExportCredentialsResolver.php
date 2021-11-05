<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Config;

use Ibexa\PersonalizationClient\Value\Config\ExportCredentials;
use Ibexa\PersonalizationClient\Value\ExportMethod;
use Ibexa\PersonalizationClient\Value\Parameters;

final class ExportCredentialsResolver extends CredentialsResolver
{
    public function getCredentials(?string $siteAccess = null): ?ExportCredentials
    {
        $requiredCredentials = $this->getRequiredCredentials($siteAccess);

        if (
            $requiredCredentials[ExportCredentials::METHOD_KEY] === ExportMethod::USER
            && !$this->hasCredentials($siteAccess)
        ) {
            return null;
        }

        return ExportCredentials::fromArray($requiredCredentials);
    }

    /**
     * @phpstan-return array{
     *  'method': ?string,
     *  'login': ?string,
     *  'password': ?string,
     * }
     */
    protected function getRequiredCredentials(?string $siteAccess = null): array
    {
        return [
            ExportCredentials::METHOD_KEY => $this->configResolver->getParameter(
                'export.authentication.method',
                Parameters::NAMESPACE,
                $siteAccess
            ),
            ExportCredentials::LOGIN_KEY => $this->configResolver->getParameter(
                'export.authentication.login',
                Parameters::NAMESPACE,
                $siteAccess
            ),
            ExportCredentials::PASSWORD_KEY => $this->configResolver->getParameter(
                'export.authentication.password',
                Parameters::NAMESPACE,
                $siteAccess
            ),
        ];
    }
}

class_alias(ExportCredentialsResolver::class, 'EzSystems\EzRecommendationClient\Config\ExportCredentialsResolver');
