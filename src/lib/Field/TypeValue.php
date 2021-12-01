<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Field;

use Ibexa\Contracts\Core\Repository\Values\Content\Content;
use Ibexa\Contracts\Core\Repository\Values\Content\Field;
use Ibexa\Contracts\FieldTypeRichText\RichText\Converter as RichTextConverterInterface;
use Ibexa\FieldTypeRichText\RichText\Converter\Html5 as XmlHtml5;
use Ibexa\PersonalizationClient\Helper\ImageHelper;
use LogicException;

final class TypeValue
{
    /** @var \Ibexa\PersonalizationClient\Helper\ImageHelper */
    private $imageHelper;

    /** @var \Ibexa\Contracts\FieldTypeRichText\RichText\Converter */
    private $richHtml5Converter;

    /** @var \Ibexa\FieldTypeRichText\RichText\Converter\Html5|null */
    private $xmlHtml5Converter;

    public function __construct(
        ImageHelper $imageHelper,
        RichTextConverterInterface $richHtml5Converter,
        ?XmlHtml5 $xmlHtml5Converter
    ) {
        $this->imageHelper = $imageHelper;
        $this->richHtml5Converter = $richHtml5Converter;
        $this->xmlHtml5Converter = $xmlHtml5Converter;
    }

    /**
     * Default field value parsing.
     *
     * @param string $fieldName
     * @param mixed $args
     */
    public function __call($fieldName, $args): string
    {
        $field = array_shift($args);

        return (string) $field->value;
    }

    /**
     * @throws \Ibexa\Contracts\Core\Repository\Exceptions\InvalidArgumentException
     */
    public function ezxmltext(Field $field): string
    {
        try {
            $xml = $this->xmlHtml5Converter->convert($field->value->xml);
        } catch (LogicException $e) {
            $xml = $field->value->xml->saveHTML();
        }

        return '<![CDATA[' . $xml . ']]>';
    }

    /**
     * Method for parsing ezrichtext field.
     */
    public function ezrichtext(Field $field): string
    {
        return '<![CDATA[' . $this->richHtml5Converter->convert($field->value->xml)->saveHTML() . ']]>';
    }

    /**
     * Method for parsing ezimage field.
     */
    public function ezimage(Field $field, Content $content, string $language, string $imageFieldIdentifier, array $options = []): string
    {
        if (!isset($field->value->id)) {
            return '';
        }

        return $this->imageHelper->getImageUrl($field, $content, $options) ?? '';
    }

    /**
     * Method for parsing ezimageasset field.
     */
    public function ezimageasset(Field $field, Content $content, string $language, string $imageFieldIdentifier, array $options = []): string
    {
        if (!isset($field->value->destinationContentId)) {
            return '';
        }

        return $this->imageHelper->getImageUrl($field, $content, $options) ?? '';
    }

    /**
     * Method for parsing ezobjectrelation field.
     * For now related fields refer to images.
     */
    public function ezobjectrelation(Field $field, Content $content, string $language, string $imageFieldIdentifier, array $options = []): string
    {
        $fields = $content->getFieldsByLanguage($language);
        foreach ($fields as $type => $field) {
            if ($type == $imageFieldIdentifier) {
                return $this->ezimage($field, $content, $language, $imageFieldIdentifier, $options);
            }
        }

        return '';
    }
}

class_alias(TypeValue::class, 'EzSystems\EzRecommendationClient\Field\TypeValue');
