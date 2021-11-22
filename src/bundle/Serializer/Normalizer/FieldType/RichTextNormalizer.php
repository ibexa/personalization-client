<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use eZ\Publish\SPI\Exception\InvalidArgumentType;
use eZ\Publish\SPI\FieldType\Value;
use EzSystems\EzPlatformRichText\eZ\FieldType\RichText\Value as RichTextValue;
use EzSystems\EzPlatformRichText\eZ\RichText\Converter;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class RichTextNormalizer implements ValueNormalizerInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    private Converter $richHtml5Converter;

    public function __construct(
        Converter $richHtml5Converter,
        ?LoggerInterface $logger = null
    ) {
        $this->richHtml5Converter = $richHtml5Converter;
        $this->logger = $logger ?? new NullLogger();
    }

    public function normalize(Value $value): ?string
    {
        if (!$value instanceof RichTextValue) {
            throw new InvalidArgumentType('$value', RichTextValue::class);
        }

        $convertedString = $this->richHtml5Converter->convert($value->xml)->saveHTML();

        if (false === $convertedString) {
            $this->logger->warning('Failed to convert xml: ' . $value);

            return null;
        }

        return $convertedString;
    }

    public function supportsValue(Value $value): bool
    {
        return $value instanceof RichTextValue;
    }
}
