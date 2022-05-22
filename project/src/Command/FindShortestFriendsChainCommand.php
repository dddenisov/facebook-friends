<?php

namespace App\Command;

use App\Service\FacebookFriendService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'ff:shortest:chain',
    description: 'Find shortest path between 2 members via their friends',
)]
class FindShortestFriendsChainCommand extends Command
{
    public function __construct(private FacebookFriendService $facebookFriendService)
    {
        parent::__construct('ff:shortest:chain');
    }

    protected function configure(): void
    {
        $this->addArgument('startUserId', InputArgument::REQUIRED, 'Start user id')
            ->addArgument('endUserId', InputArgument::REQUIRED, 'End user id');
    }

    /**
     * @throws NonUniqueResultException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $startUserId = (int) $input->getArgument('startUserId');
        $endUserId = (int) $input->getArgument('endUserId');

        $chainFriendIds = $this->facebookFriendService->findShortestChainBetweenFriends($startUserId, $endUserId);

        if (empty($chainFriendIds)) {
            $output->writeln('There are no mutual friends between these members');
        } else {
            $output->writeln(implode(' -> ', $chainFriendIds));
        }

        return Command::SUCCESS;
    }
}
