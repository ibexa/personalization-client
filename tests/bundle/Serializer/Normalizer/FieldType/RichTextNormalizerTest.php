<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType;

use DOMDocument;
use EzSystems\EzPlatformRichText\eZ\FieldType\RichText\Value;
use EzSystems\EzPlatformRichText\eZ\RichText\Converter;
use Ibexa\Bundle\PersonalizationClient\Serializer\Normalizer\FieldType\RichTextNormalizer;
use Ibexa\Contracts\PersonalizationClient\Serializer\Normalizer\ValueNormalizerInterface;

final class RichTextNormalizerTest extends AbstractValueNormalizerTestCase
{
    /** @var \EzSystems\EzPlatformRichText\eZ\RichText\Converter|mixed|\PHPUnit\Framework\MockObject\MockObject */
    private Converter $converter;

    protected function setUp(): void
    {
        $this->converter = $this->createMock(Converter::class);
    }

    /**
     * @dataProvider provideDataForTestNormalize
     * @dataProvider provideEmptyDataForTestNormalize
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function testNormalizer(
        string $expected,
        DOMDocument $input,
        DOMDocument $output,
        Value $value
    ): void {
        $this->converter
            ->expects(self::once())
            ->method('convert')
            ->with($input)
            ->willReturn($output);

        $this->testNormalize($expected, $value);
    }

    /**
     * @return iterable<array{
     *  string,
     *  DOMDocument,
     *  DOMDocument,
     *  \EzSystems\EzPlatformRichText\eZ\FieldType\RichText\Value
     * }>
     */
    public function provideDataForTestNormalize(): iterable
    {
        $xml =
            '<?xml version="1.0" encoding="UTF-8"?>
            <section 
                xmlns="http://docbook.org/ns/docbook" 
                xmlns:xlink="http://www.w3.org/1999/xlink" 
                xmlns:ezxhtml="http://ez.no/xmlns/ezpublish/docbook/xhtml" 
                xmlns:ezcustom="http://ez.no/xmlns/ezpublish/docbook/custom" 
                version="5.0-variant ezpublish-1.0"
            >
                <para>
                    <emphasis role="strong">You are now ready to start your project.</emphasis>
                </para>
            </section>';

        $input = new DOMDocument();
        $input->loadXML($xml);

        $expectedOutput = '<p><strong>You are now ready to start your project.</strong></p>' . PHP_EOL;
        $output = new DOMDocument();
        $output->loadXML($expectedOutput);

        yield [
            $expectedOutput,
            $input,
            $output,
            new Value($xml),
        ];
    }

    /**
     * @return iterable<array{
     *  string,
     *  DOMDocument,
     *  DOMDocument,
     *  \EzSystems\EzPlatformRichText\eZ\FieldType\RichText\Value
     * }>
     */
    public function provideEmptyDataForTestNormalize(): iterable
    {
        $xml =
            '<?xml version="1.0" encoding="UTF-8"?>
            <section xmlns="http://docbook.org/ns/docbook" 
            xmlns:xlink="http://www.w3.org/1999/xlink" 
            version="5.0-variant ezpublish-1.0"
            />';

        $emptyInput = new DOMDocument();
        $emptyInput->loadXML($xml);

        yield [
            PHP_EOL,
            $emptyInput,
            new DOMDocument(),
            new Value(),
        ];
    }

    protected function getNormalizer(): ValueNormalizerInterface
    {
        return new RichTextNormalizer($this->converter);
    }

    protected function getValue(): Value
    {
        return new Value();
    }
}
