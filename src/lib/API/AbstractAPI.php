<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\API;

use GuzzleHttp\Psr7\Uri;
use Ibexa\PersonalizationClient\Client\EzRecommendationClientInterface;
use Ibexa\PersonalizationClient\SPI\Request;
use Psr\Http\Message\UriInterface;

abstract class AbstractAPI
{
    /** @var \Ibexa\PersonalizationClient\Client\EzRecommendationClientInterface */
    protected $client;

    /** @var \GuzzleHttp\Psr7\Uri */
    protected $endPointUri;

    public function __construct(EzRecommendationClientInterface $client, string $endPointUri)
    {
        $this->client = $client;
        $this->endPointUri = $endPointUri;
    }

    protected function getEndPointUri(): UriInterface
    {
        return new Uri($this->endPointUri);
    }

    /**
     * @param string $rawEndPointUri
     */
    protected function buildEndPointUri(array $endPointParameters, ?string $rawEndPointUri = null): UriInterface
    {
        if (!$endPointParameters) {
            return $this->getEndPointUri();
        }

        if ($rawEndPointUri) {
            return new Uri(vsprintf($rawEndPointUri, $endPointParameters));
        }

        return new Uri(vsprintf($this->endPointUri, $endPointParameters));
    }

    protected function buildQueryStringFromArray(array $parameters): string
    {
        $queryString = '';

        foreach ($parameters as $parameterKey => $parameterValue) {
            if (\is_array($parameterValue)) {
                $queryString .= $this->buildQueryStringFromArray($parameterValue);
            }

            if (\is_string($parameterValue) || \is_numeric($parameterValue)) {
                $queryString .= $parameterKey . '=' . (string) $parameterValue;
            }

            if (next($parameters)) {
                $queryString .= '&';
            }
        }

        return $queryString;
    }

    protected function getQueryStringParameters(Request $request, array $requiredAttributes = []): array
    {
        if ($requiredAttributes) {
            return array_intersect_key($request->getRequestAttributes(), array_flip($requiredAttributes));
        }

        return $request->getRequestAttributes();
    }
}

class_alias(AbstractAPI::class, 'EzSystems\EzRecommendationClient\API\AbstractAPI');
