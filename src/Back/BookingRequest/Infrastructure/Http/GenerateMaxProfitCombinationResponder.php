<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Infrastructure\Http;

use App\Back\BookingRequest\Domain\MaximizeResponse;
use App\Back\BookingRequest\Domain\MaximizeResponseContract;
use App\Back\BookingRequest\Domain\StatsResponseContract;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GenerateMaxProfitCombinationResponder
{
    /** @var string[] */
    private array $errors = [];

    private Response $response;

    public function loadError(string $error): void
    {
        $this->errors[] = $error;
    }

    public function loadBestCombination(MaximizeResponse $bestProfitCombination): void
    {
        $this->response = new JsonResponse(
            [
                MaximizeResponseContract::REQUEST_IDS => $bestProfitCombination->requestIds(),
                MaximizeResponseContract::TOTAL_PROFIT => $bestProfitCombination->totalProfit(),
                StatsResponseContract::AVG_NIGHT => $bestProfitCombination->avgNight(),
                StatsResponseContract::MIN_NIGHT => $bestProfitCombination->minNight(),
                StatsResponseContract::MAX_NIGHT => $bestProfitCombination->maxNight()
            ],
            Response::HTTP_OK,
        );
    }

    public function response(): Response
    {
        if (!empty($this->errors)) {
            return new JsonResponse(
                $this->errors,
                Response::HTTP_BAD_REQUEST,
            );
        }
        return $this->response;
    }
}