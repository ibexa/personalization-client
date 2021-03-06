<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Bundle\PersonalizationClient\Command;

use Ibexa\PersonalizationClient\Exception\InvalidArgumentException;
use Ibexa\PersonalizationClient\Exception\MissingExportParameterException;

final class ExportCommandTest extends AbstractCommandTestCase
{
    protected static function getCommandName(): string
    {
        return 'ibexa:recommendation:run-export';
    }

    public function testThrowExceptionWhenInvalidSiteAccessIsPassed(): void
    {
        $siteAccess = 'undefined_siteaccess';
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf('SiteAccess %s doesn\'t exist', $siteAccess)
        );
        $this->commandTester->execute(
            [
                '--siteaccess' => $siteAccess,
            ]
        );
    }

    /**
     * @param array<string, string> $parameters
     *
     * @dataProvider provideForTestCommandThrowExceptionWhenRequiredParametersAreMissing
     */
    public function testThrowExceptionWhenRequiredParametersAreMissing(
        array $parameters,
        string $expectExceptionMessage
    ): void {
        $this->expectException(MissingExportParameterException::class);
        $this->expectExceptionMessage($expectExceptionMessage);
        $this->commandTester->execute($parameters);
    }

    /**
     * @return array<array{array<string>, string}>.
     */
    public function provideForTestCommandThrowExceptionWhenRequiredParametersAreMissing(): array
    {
        return [
            [
                [
                    '--item-type-identifier-list' => 'product, article, blog_post',
                    '--languages=eng-GB',
                    '--customer-id' => '12345',
                ],
                'Required parameters: license-key, siteaccess are missing',
            ],
            [
                [
                    '--item-type-identifier-list' => 'product, article, blog_post',
                    '--languages=eng-GB',
                    '--license-key' => '12345-12345-12345-12345',
                ],
                'Required parameters: customer-id, siteaccess are missing',
            ],
            [
                [
                    '--item-type-identifier-list' => 'product, article, blog_post',
                    '--languages=eng-GB',
                    '--siteaccess' => 'first_siteaccess',
                ],
                'Required parameters: customer-id, license-key are missing',
            ],
        ];
    }
}
