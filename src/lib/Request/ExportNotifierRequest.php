<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Request;

use Ibexa\PersonalizationClient\SPI\Request;

class ExportNotifierRequest extends Request
{
    public const ACTION_KEY = 'action';
    public const FORMAT_KEY = 'format';
    public const CONTENT_TYPE_ID_KEY = 'contentTypeId';
    public const CONTENT_TYPE_NAME_KEY = 'contentTypeName';
    public const LANG_KEY = 'lang';
    public const URI_KEY = 'uri';
    public const CREDENTIALS_KEY = 'credentials';

    /** @var string */
    public $action;

    /** @var string */
    public $format;

    /** @var int */
    public $contentTypeId;

    /** @var string */
    public $contentTypeName;

    /** @var string|null */
    public $lang;

    /** @var string */
    public $uri;

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
            'contentTypeId' => $this->contentTypeId,
            'contentTypeName' => $this->contentTypeName,
            'lang' => $this->lang,
            'uri' => $this->uri,
            'credentials' => $this->credentials,
        ];
    }
}

class_alias(ExportNotifierRequest::class, 'EzSystems\EzRecommendationClient\Request\ExportNotifierRequest');
