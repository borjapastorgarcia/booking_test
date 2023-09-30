<?php

declare(strict_types=1);


namespace App\Back\BookingRequest\Infrastructure\Http;


use App\Back\BookingRequest\Domain\StatsResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class GenerateStatsResponder
{
    /** @var string[] */
    private array $errors = [];

    private Response $response;

    public function loadError(string $error): void
    {
        $this->errors[] = $error;
    }

    public function loadStats(StatsResponse $stats): void
    {
        $this->response = new JsonResponse(
            [
                'avg_night' => $stats->avgNight(),
                'min_night' => $stats->minNight(),
                'max_night' => $stats->maxNight()
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