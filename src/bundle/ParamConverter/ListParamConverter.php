<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\ParamConverter;

use Ibexa\PersonalizationClient\Exception\InvalidArgumentException;
use Ibexa\PersonalizationClient\Helper\ParamsConverterHelper;
use Ibexa\PersonalizationClient\Value\IdList;
use Ibexa\Rest\Server\Exceptions\BadRequestException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ListParamConverter implements ParamConverterInterface
{
    /**
     * {@inheritdoc}
     */
    public function apply(Request $request, ParamConverter $configuration): bool
    {
        $paramName = $configuration->getName();

        if (!$request->attributes->has($paramName)) {
            return false;
        }

        try {
            $idListAsString = $request->attributes->get($paramName);
            $idList = new IdList();
            $idList->list = ParamsConverterHelper::getIdListFromString($idListAsString);
            $request->attributes->set($paramName, $idList);

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
        return IdList::class === $configuration->getClass();
    }
}

class_alias(ListParamConverter::class, 'EzSystems\EzRecommendationClientBundle\ParamConverter\ListParamConverter');
