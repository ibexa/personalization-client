<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\ParamConverter;

use Ibexa\PersonalizationClient\Exception\InvalidArgumentException;
use Ibexa\PersonalizationClient\Mapper\ExportRequestMapper;
use Ibexa\PersonalizationClient\Value\ExportRequest;
use Ibexa\Rest\Server\Exceptions\BadRequestException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ExportRequestParamConverter implements ParamConverterInterface
{
    /** @var \Ibexa\PersonalizationClient\Mapper\ExportRequestMapper */
    private $exportRequestMapper;

    public function __construct(ExportRequestMapper $exportRequestMapper)
    {
        $this->exportRequestMapper = $exportRequestMapper;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        try {
            $exportRequest = $this->exportRequestMapper->getExportRequest($request);
            $paramName = $configuration->getName();

            $request->attributes->set($paramName, $exportRequest);

            return true;
        } catch (InvalidArgumentException $e) {
            throw new BadRequestException('Bad Request', Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return ExportRequest::class === $configuration->getClass();
    }
}

class_alias(ExportRequestParamConverter::class, 'EzSystems\EzRecommendationClientBundle\ParamConverter\ExportRequestParamConverter');
