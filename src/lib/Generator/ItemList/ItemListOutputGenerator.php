<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Generator\ItemList;

use Ibexa\Contracts\PersonalizationClient\Value\ItemListInterface;
use Ibexa\Contracts\Rest\Output\Generator;

final class ItemListOutputGenerator implements ItemListOutputGeneratorInterface
{
    public function generate(Generator $generator, ItemListInterface $itemList): Generator
    {
        $generator->startObjectElement('contentList');
        $generator->startList('content');

        /** @var \Ibexa\Contracts\PersonalizationClient\Value\ItemInterface $item */
        foreach ($itemList as $item) {
            $itemType = $item->getType();

            $generator->startObjectElement('content');
            $generator->valueElement('contentId', $item->getId());
            $generator->valueElement('contentTypeId', $itemType->getId());
            $generator->valueElement('contentTypeIdentifier', $itemType->getIdentifier());
            $generator->valueElement('itemTypeName', $itemType->getName());
            $generator->valueElement('language', $item->getLanguage());

            foreach ($item->getAttributes() as $name => $value) {
                $generator->valueElement($name, $value);
            }

            $generator->endObjectElement('content');
        }

        $generator->endList('content');
        $generator->endObjectElement('contentList');

        return $generator;
    }

    public function getOutput(Generator $generator, ItemListInterface $itemList): string
    {
        $generator->reset();
        $generator->startDocument($itemList);
        $generator = $this->generate($generator, $itemList);

        return $generator->endDocument($itemList);
    }
}
