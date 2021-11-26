<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use eZ\Publish\Core\FieldType\Author\Value as AuthorValue;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentType;
use eZ\Publish\SPI\FieldType\Value;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;

final class AuthorNormalizer implements ValueNormalizerInterface
{
    /**
     * @return array<string>
     */
    public function normalize(Value $value): array
    {
        if (!$value instanceof AuthorValue) {
            throw new InvalidArgumentType('$value', AuthorValue::class, $value);
        }

        $authors = [];

        /** @var \eZ\Publish\Core\FieldType\Author\Author $author */
        foreach ($value->authors as $author) {
            $authors[] = $author->name;
        }

        return $authors;
    }

    public function supportsValue(Value $value): bool
    {
        return $value instanceof AuthorValue;
    }
}
