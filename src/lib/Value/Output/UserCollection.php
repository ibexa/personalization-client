<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\PersonalizationClient\Value\Output;

use Webmozart\Assert\Assert;

class UserCollection
{
    /** @var \Ibexa\PersonalizationClient\Value\Output\User[] */
    private $users = [];

    /**
     * @param \Ibexa\PersonalizationClient\Value\Output\User[] $users
     */
    public function __construct(array $users = [])
    {
        Assert::nullOrAllIsInstanceOf($users, User::class);
        $this->users = $users;
    }

    /**
     * @return \Ibexa\PersonalizationClient\Value\Output\User[]
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    public function isEmpty(): bool
    {
        return 0 === \count($this->users);
    }
}

class_alias(UserCollection::class, 'EzSystems\EzRecommendationClient\Value\Output\UserCollection');
