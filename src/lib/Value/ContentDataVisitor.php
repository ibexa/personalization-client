<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Value;

use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\Contracts\Rest\Output\ValueObjectVisitor;
use Ibexa\Contracts\Rest\Output\Visitor;
use Ibexa\PersonalizationClient\Exception\ResponseClassNotImplementedException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * ContentDataVisitor converter for REST output.
 */
class ContentDataVisitor extends ValueObjectVisitor
{
    /** @var array */
    private $responseRenderers = [];

    /**
     * @param array $responseRenderers
     */
    public function setResponseRenderers($responseRenderers)
    {
        $this->responseRenderers = $responseRenderers;
    }

    /**
     * @param mixed $data
     *
     * @throws \Ibexa\PersonalizationClient\Exception\ResponseClassNotImplementedException
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'responseType' => 'http',
        ]);

        $data->options = $resolver->resolve($data->options);

        $visitor->setHeader('Content-Type', $generator->getMediaType('ContentList'));

        if (empty($data->contents)) {
            $visitor->setStatus(204);

            return;
        }

        if (!isset($this->responseRenderers[$data->options['responseType']])) {
            throw new ResponseClassNotImplementedException(sprintf('Renderer for %s response not implemented.', $data->options['responseType']));
        }

        return $this->responseRenderers[$data->options['responseType']]->render($generator, $data);
    }
}

class_alias(ContentDataVisitor::class, 'EzSystems\EzRecommendationClient\Value\ContentDataVisitor');
