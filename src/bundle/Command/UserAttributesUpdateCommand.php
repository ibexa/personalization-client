<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Bundle\PersonalizationClient\Command;

use Ibexa\Bundle\Core\Command\BackwardCompatibleCommand;
use Ibexa\PersonalizationClient\Client\EzRecommendationClientInterface;
use Ibexa\PersonalizationClient\Event\UpdateUserAPIEvent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;

final class UserAttributesUpdateCommand extends Command implements BackwardCompatibleCommand
{
    /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface */
    private $eventDispatcher;

    /** @var \Ibexa\PersonalizationClient\Client\EzRecommendationClientInterface */
    private $client;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        EzRecommendationClientInterface $client
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->client = $client;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('ibexa:recommendation:update-user')
            ->setAliases(['ezrecommendation:user:update'])
            ->setDescription('Update the set of the user attributes');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $event = new UpdateUserAPIEvent();
        $this->eventDispatcher->dispatch($event);

        $request = $event->getUserAPIRequest();

        $output->writeln([
            'Updating user attributes',
            '',
        ]);

        if (!$request) {
            $output->writeln('<fg=red>Request object is empty</>');

            return;
        } elseif (!$request->source) {
            $output->writeln('<fg=red>Property source is not defined</>');

            return;
        } elseif (!$request->xmlBody) {
            $output->writeln('<fg=red>Property xmlBody is not defined</>');

            return;
        }

        $response = $this->client->user()->updateUserAttributes($request);

        if ($response && $response->getStatusCode() === Response::HTTP_OK) {
            $output->writeln('<fg=green>User attributes updated successfully!</>');
        }
    }

    /**
     * @return string[]
     */
    public function getDeprecatedAliases(): array
    {
        return ['ezrecommendation:user:update'];
    }
}

class_alias(UserAttributesUpdateCommand::class, 'EzSystems\EzRecommendationClientBundle\Command\UserAttributesUpdateCommand');
