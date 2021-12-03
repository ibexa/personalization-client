<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PersonalizationClient\Config;

use Ibexa\Core\MVC\ConfigResolverInterface;
use Ibexa\PersonalizationClient\Config\ExportCredentialsResolver;
use Ibexa\PersonalizationClient\Value\Config\ExportCredentials;
use PHPUnit\Framework\TestCase;

class ExportCredentialsResolverTest extends TestCase
{
    /** @var \PHPUnit\Framework\MockObject\MockObject|\Ibexa\Core\MVC\ConfigResolverInterface */
    private $configResolver;

    protected function setUp(): void
    {
        $this->configResolver = $this->getMockBuilder(ConfigResolverInterface::class)->getMock();

        parent::setUp();
    }

    public function testCreateExportCredentialsResolverInstance(): void
    {
        $this->assertInstanceOf(ExportCredentialsResolver::class, new ExportCredentialsResolver(
            $this->configResolver
        ));
    }

    public function testGetCredentialsForAuthenticationMethodUser(): void
    {
        $credentialsResolver = new ExportCredentialsResolver(
            $this->configResolver
        );

        $this->assertInstanceOf(ExportCredentials::class, $credentialsResolver->getCredentials());
    }

    public function testReturnNullWhenMethodIsUserAndHasCredentialsIsFalse(): void
    {
        $this->configResolver
            ->expects($this->at(0))
            ->method('getParameter')
            ->with('export.authentication.method', 'ezrecommendation')
            ->willReturn('user');

        $credentialsResolver = new ExportCredentialsResolver(
            $this->configResolver,
        );

        $this->assertNull($credentialsResolver->getCredentials());
    }
}

class_alias(ExportCredentialsResolverTest::class, 'EzSystems\EzRecommendationClient\Tests\Config\ExportCredentialsResolverTest');
