<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Value\Output;

use Webmozart\Assert\Assert;

class Attribute
{
    public const TYPE_NUMERIC = 'NUMERIC';
    public const TYPE_NOMINAL = 'NOMINAL';
    public const TYPE_TEXT = 'TEXT';
    public const TYPE_DATE = 'DATE';
    public const TYPE_DATETIME = 'DATETIME';

    public const DEFAULT_TYPE = self::TYPE_NOMINAL;

    public const TYPES = [
        self::TYPE_NUMERIC,
        self::TYPE_NOMINAL,
        self::TYPE_TEXT,
        self::TYPE_DATE,
        self::TYPE_DATETIME,
    ];

    /** @var string */
    private $name;

    /** @var string */
    private $value;

    /** @var string */
    private $type;

    public function __construct(string $name, string $value, string $type = self::DEFAULT_TYPE)
    {
        Assert::notNull($name);
        Assert::keyExists(array_flip(self::TYPES), $type, 'Wrong Attribute type.');

        $this->name = $name;
        $this->value = $value;
        $this->type = $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getType(): string
    {
        return $this->type;
    }
}

class_alias(Attribute::class, 'EzSystems\EzRecommendationClient\Value\Output\Attribute');
