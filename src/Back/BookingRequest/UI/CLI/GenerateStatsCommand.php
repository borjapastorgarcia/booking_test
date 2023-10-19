<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\UI\CLI;

use App\Back\BookingRequest\Application\Stats\Generate\GenerateBookingRequestStatsQuery;
use App\Back\BookingRequest\Application\Stats\StatsResponse;
use App\Back\BookingRequest\Infrastructure\Http\GenerateStatsResponder;
use App\Back\Shared\Domain\Bus\Query\QueryBus;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class GenerateStatsCommand extends Command
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GenerateStatsResponder $responder
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        parent::configure();
        $this->setDescription('
Given a list of booking requests, return the average, minimum, and maximum profit per night taking into account all the 
booking requests in the payload.')
            ->setName('booking:generate:stats')
            ->addArgument(
                'request',
                InputArgument::OPTIONAL,
                'Booking request (plain data)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            /** @var StatsResponse $stats */
            $stats = $this->queryBus->ask(
                new GenerateBookingRequestStatsQuery(
                    $input->getArgument('request')
                )
            );
            $this->responder->loadStats($stats);
        } catch (Exception $exception) {
            $this->responder->loadError($exception->getMessage());
            return 0;
        }
        $output->writeln($this->responder->response()->getContent());
        $output->writeln("\n");
        return 1;
    }
}