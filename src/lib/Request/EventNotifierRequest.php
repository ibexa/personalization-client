<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Request;

use Ibexa\PersonalizationClient\SPI\Request;

class EventNotifierRequest extends Request
{
    public const ACTION_KEY = 'action';
    public const FORMAT_KEY = 'format';
    public const URI_KEY = 'uri';
    public const ITEM_ID_KEY = 'itemId';
    public const CONTENT_TYPE_ID_KEY = 'contentTypeId';
    public const LANG_KEY = 'lang';
    public const CREDENTIALS_KEY = 'credentials';

    /** @var string */
    public $action;

    /** @var string */
    public $format;

    /** @var string */
    public $uri;

    /** @var int */
    public $itemId;

    /** @var int */
    public $contentTypeId;

    /** @var string|null */
    public $lang;

    /** @var array */
    public $credentials;

    public function __construct(array $parameters)
    {
        parent::__construct($this, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestAttributes(): array
    {
        return [
            'action' => $this->action,
            'format' => $this->format,
            'uri' => $this->uri,
            'itemId' => $this->itemId,
            'contentTypeId' => $this->contentTypeId,
            'lang' => $this->lang,
            'credentials' => $this->credentials,
        ];
    }
}

class_alias(EventNotifierRequest::class, 'EzSystems\EzRecommendationClient\Request\EventNotifierRequest');
