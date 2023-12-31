<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Infrastructure\Http;

use App\Back\BookingRequest\Application\Maximize\MaximizeProfitResponse;
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

    public function loadBestCombination(MaximizeProfitResponse $greatestProfitCombination): void
    {
        $this->response = new JsonResponse(
            $greatestProfitCombination->toArray(),
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