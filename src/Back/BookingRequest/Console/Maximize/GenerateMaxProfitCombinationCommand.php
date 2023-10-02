<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Console\Maximize;

use App\Back\BookingRequest\Application\Maximize\GenerateMaxCombinationProfit\GenerateMaximizeProfitBookingRequestQuery;
use App\Back\BookingRequest\Domain\MaximizeResponse;
use App\Back\BookingRequest\Infrastructure\Http\GenerateMaxProfitCombinationResponder;
use App\Back\Shared\Domain\Bus\Query\QueryBus;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class GenerateMaxProfitCombinationCommand extends Command
{
    public function __construct(
        private readonly QueryBus $queryBus,
        private readonly GenerateMaxProfitCombinationResponder $responder
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        parent::configure();
        $this->setDescription('Given a list of booking requests, return the best combination of requests that maximizes total profits.')
            ->setName('stayforlong:generate:max_profit')
            ->addArgument(
                'request',
                InputArgument::OPTIONAL,
                'Booking request (plain data)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $request = $input->getArgument('request');
        try {
            /** @var MaximizeResponse $bestCombinationResponse */
            $bestCombinationResponse = $this->queryBus->ask(
                new GenerateMaximizeProfitBookingRequestQuery(
                    $request
                )
            );
            $this->responder->loadBestCombination($bestCombinationResponse);

        } catch (Exception $exception) {
            $this->responder->loadError($exception->getMessage());
            return 0;
        }
        $output->writeln($this->responder->response()->getContent());
        $output->writeln("\n");
        return 1;
    }
}