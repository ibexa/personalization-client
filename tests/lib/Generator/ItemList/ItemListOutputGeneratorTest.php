<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\PersonalizationClient\Generator\ItemList;

use Ibexa\Contracts\Rest\Output\Generator;
use Ibexa\PersonalizationClient\Generator\ItemList\ItemListOutputGenerator;
use Ibexa\PersonalizationClient\Generator\ItemList\ItemListOutputGeneratorInterface;
use Ibexa\Rest\Output\Generator\Json;
use Ibexa\Tests\PersonalizationClient\Storage\AbstractDataSourceTestCase;

final class ItemListOutputGeneratorTest extends AbstractDataSourceTestCase
{
    private ItemListOutputGeneratorInterface $itemListElementGenerator;

    /** @var \Ibexa\Rest\Output\Generator\Json|\PHPUnit\Framework\MockObject\MockObject */
    private Generator $outputGenerator;

    protected function setUp(): void
    {
        $this->itemListElementGenerator = new ItemListOutputGenerator();
        $this->outputGenerator = $this->createMock(Json::class);
    }

    public function testGenerate(): void
    {
        $articles = $this->itemCreator->createTestItemListForEnglishArticles();

        $this->outputGenerator
            ->expects(self::atLeastOnce())
            ->method('startDocument')
            ->with($articles);

        $this->outputGenerator
            ->expects(self::atLeastOnce())
            ->method('startObjectElement')
            ->withConsecutive(
                ['contentList'],
                ['content'],
                ['content'],
            );

        $this->outputGenerator
            ->expects(self::once())
            ->method('startList')
            ->with('content');

        $this->outputGenerator
            ->expects(self::atLeastOnce())
            ->method('valueElement')
            ->withConsecutive(
                ['contentId', '1'],
                ['contentTypeId', '1'],
                ['contentTypeIdentifier', 'article'],
                ['itemTypeName', 'Article'],
                ['language', 'en'],
                ['name', 'Article 1 en'],
                ['body', 'Article 1 body en'],
                ['image', 'public/var/1/2/4/5/article/1'],
                ['contentId', '2'],
                ['contentTypeId', '1'],
                ['contentTypeIdentifier', 'article'],
                ['itemTypeName', 'Article'],
                ['language', 'en'],
                ['name', 'Article 2 en'],
                ['body', 'Article 2 body en'],
                ['image', 'public/var/1/2/4/5/article/2'],
            );

        $this->outputGenerator
            ->expects(self::atLeastOnce())
            ->method('endObjectElement')
            ->withConsecutive(
                ['content'],
                ['content'],
                ['contentList'],
            );

        $this->outputGenerator
            ->expects(self::once())
            ->method('endList')
            ->with('content');

        $this->outputGenerator
            ->expects(self::atLeastOnce())
            ->method('endDocument')
            ->with($articles)
            ->willReturn($this->getOutput());

        self::assertEquals(
            $this->getOutput(),
            $this->itemListElementGenerator->getOutput(
                $this->outputGenerator,
                $articles
            )
        );
    }

    private function getOutput(): string
    {
        return '{"contentList":{"_media-type":"application\/vnd.ez.api.contentList+json","content":[{"_media-type":"application\/vnd.ez.api.content+json","contentId":"1","contentTypeId":"1","contentTypeIdentifier":"article","itemTypeName":"Article","language":"en","name":"Article 1 en","body":"Article 1 body en","image":"public\/var\/1\/2\/4\/5\/article\/1"},{"_media-type":"application\/vnd.ez.api.content+json","contentId":"2","contentTypeId":"1","contentTypeIdentifier":"article","itemTypeName":"Article","language":"en","name":"Article 2 en","body":"Article 2 body en","image":"public\/var\/1\/2\/4\/5\/article\/2"}]}}';
    }
}
