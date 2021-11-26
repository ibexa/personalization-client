<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use eZ\Publish\Core\Base\Exceptions\InvalidArgumentType;
use eZ\Publish\Core\FieldType\EmailAddress\Value as EmailAddressValue;
use eZ\Publish\SPI\FieldType\Value;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;

final class EmailAddressNormalizer implements ValueNormalizerInterface
{
    public function normalize(Value $value): string
    {
        if (!$value instanceof EmailAddressValue) {
            throw new InvalidArgumentType('$value', EmailAddressValue::class);
        }

        return $value->email;
    }

    public function supportsValue(Value $value): bool
    {
        return $value instanceof EmailAddressValue;
    }
}
