<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Factory;

use eZ\Publish\Core\MVC\ConfigResolverInterface;
use Ibexa\PersonalizationClient\API\AbstractAPI;
use Ibexa\PersonalizationClient\API\AllowedAPI;
use Ibexa\PersonalizationClient\Client\EzRecommendationClientInterface;
use Ibexa\PersonalizationClient\Exception\BadAPICallException;
use Ibexa\PersonalizationClient\Exception\InvalidArgumentException;
use Ibexa\PersonalizationClient\Value\Parameters;

final class EzRecommendationClientAPIFactory extends AbstractEzRecommendationClientAPIFactory
{
    /** @var \EzSystems\EzRecommendationClient\API\AllowedAPI */
    private $allowedAPI;

    /** @var \eZ\Publish\Core\MVC\ConfigResolverInterface */
    private $configResolver;

    public function __construct(AllowedAPI $allowedApi, ConfigResolverInterface $configResolver)
    {
        $this->allowedAPI = $allowedApi;
        $this->configResolver = $configResolver;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \EzSystems\EzRecommendationClient\Exception\InvalidArgumentException
     * @throws \EzSystems\EzRecommendationClient\Exception\BadAPICallException
     */
    public function buildAPI(string $name, EzRecommendationClientInterface $client): AbstractAPI
    {
        if (!\array_key_exists($name, $this->allowedAPI->getAllowedAPI())) {
            throw new InvalidArgumentException(sprintf('Given api key: %s is not found in allowedApi array', $name));
        }

        $api = $this->allowedAPI->getAllowedAPI()[$name];

        if (!class_exists($api)) {
            throw new BadAPICallException($api);
        }

        $endPoint = $this->getApiEndPoint($name);

        return new $api($client, $endPoint);
    }

    private function getApiEndPoint(string $apiName): string
    {
        $parameterName = $this->getApiEndPointParameterName($apiName);

        return $this->configResolver->getParameter(
            Parameters::API_SCOPE . '.' . $parameterName . '.endpoint',
            Parameters::NAMESPACE
        );
    }

    private function getApiEndPointParameterName(string $apiName): string
    {
        return ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $apiName)), '_');
    }
}

class_alias(EzRecommendationClientAPIFactory::class, 'EzSystems\EzRecommendationClient\Factory\EzRecommendationClientAPIFactory');
