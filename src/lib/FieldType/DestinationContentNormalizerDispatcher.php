<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\FieldType;

use Ibexa\Contracts\Core\Repository\ContentService;
use Ibexa\Contracts\Core\Repository\Repository;
use Ibexa\Contracts\Core\Repository\Values\Content\Content;

final class DestinationContentNormalizerDispatcher implements DestinationContentNormalizerDispatcherInterface
{
    /** @var iterable<\Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\DestinationValueAwareInterface> */
    private iterable $normalizers;

    private ContentService $contentService;

    private Repository $repository;

    /**
     * @param iterable<\Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\DestinationValueAwareInterface> $normalizers
     */
    public function __construct(
        ContentService $contentService,
        Repository $repository,
        iterable $normalizers
    ) {
        $this->contentService = $contentService;
        $this->repository = $repository;
        $this->normalizers = $normalizers;
    }

    public function dispatch(int $destinationContentId)
    {
        $fields = $this->getContent($destinationContentId)->getFields();
        foreach ($fields as $field) {
            foreach ($this->normalizers as $normalizer) {
                $value = $field->value;
                if ($normalizer->supportsValue($value)) {
                    return $normalizer->normalize($value);
                }
            }
        }

        return null;
    }

    private function getContent(int $contentId): Content
    {
        return $this->repository->sudo(fn () => $this->contentService->loadContent($contentId));
    }
}
