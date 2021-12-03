<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PersonalizationClient\Factory;

use Ibexa\Core\MVC\ConfigResolverInterface;
use Ibexa\PersonalizationClient\API\AllowedAPI;
use Ibexa\PersonalizationClient\Client\EzRecommendationClientInterface;
use Ibexa\PersonalizationClient\Exception\BadAPICallException;
use Ibexa\PersonalizationClient\Exception\InvalidArgumentException;
use Ibexa\PersonalizationClient\Factory\AbstractEzRecommendationClientAPIFactory;
use Ibexa\PersonalizationClient\Factory\EzRecommendationClientAPIFactory;
use Ibexa\Tests\PersonalizationClient\API\APIEndPointClassTest;
use PHPUnit\Framework\TestCase;

class EzRecommendationClientAPIFactoryTest extends TestCase
{
    /** @var \Ibexa\PersonalizationClient\Factory\EzRecommendationClientAPIFactory */
    private $apiFactory;

    /** @var \Ibexa\Core\MVC\ConfigResolverInterface|\PHPUnit\Framework\MockObject\MockObject */
    private $configResolverMock;

    /** @var \Ibexa\PersonalizationClient\API\AllowedAPI|\PHPUnit\Framework\MockObject\MockObject */
    private $allowedAPI;

    /** @var \Ibexa\PersonalizationClient\Client\EzRecommendationClientInterface|\PHPUnit\Framework\MockObject\MockObject */
    private $clientMock;

    public function setUp(): void
    {
        $this->clientMock = $this->createMock(EzRecommendationClientInterface::class);
        $this->configResolverMock = $this->createMock(ConfigResolverInterface::class);
        $this->allowedAPI = $this->createMock(AllowedAPI::class);
        $this->apiFactory = new EzRecommendationClientAPIFactory(
            $this->allowedAPI,
            $this->configResolverMock
        );
    }

    public function testCreateEzRecommendationClientApiFactoryInstance()
    {
        $this->assertInstanceOf(
            AbstractEzRecommendationClientAPIFactory::class,
            $this->apiFactory
        );
    }

    public function testThrowExceptionWhenInvalidAPIKeyIsGiven()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->apiFactory->buildAPI('invalid-api-key', $this->clientMock);
    }

    public function testThrowExceptionWhenAPIClassDoesNotExists()
    {
        $this->expectException(BadAPICallException::class);
        $this->allowedAPI
            ->expects($this->atLeastOnce())
            ->method('getAllowedApi')
            ->willReturn([
                'api-name' => 'invalid-api-class',
            ]);

        $this->apiFactory->buildAPI('api-name', $this->clientMock);
    }

    /**
     * @dataProvider apiDataProvider
     */
    public function testReturnAPIClass(string $apiName)
    {
        $this->allowedAPI
            ->expects($this->atLeastOnce())
            ->method('getAllowedApi')
            ->willReturn([
                'endpoint1' => APIEndPointClassTest::class,
                'endpoint2' => APIEndPointClassTest::class,
                'endpoint3' => APIEndPointClassTest::class,
                'endpoint4' => APIEndPointClassTest::class,
            ]);

        $this->configResolverMock
            ->expects($this->once())
            ->method('getParameter')
            ->willReturn('api.endpoint.uri');

        $this->apiFactory->buildAPI($apiName, $this->clientMock);
    }

    public function apiDataProvider(): array
    {
        return [
            ['endpoint1'],
            ['endpoint2'],
            ['endpoint3'],
            ['endpoint4'],
        ];
    }
}

class_alias(EzRecommendationClientAPIFactoryTest::class, 'EzSystems\EzRecommendationClient\Tests\Factory\EzRecommendationClientAPIFactoryTest');
