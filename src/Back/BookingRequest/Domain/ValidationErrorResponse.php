<?php

declare(strict_types=1);


namespace App\Back\BookingRequest\Domain;


use App\Back\Shared\Domain\Exception\DomainException;

final class ValidationErrorResponse extends DomainException
{
    public static function becauseRequiredFieldsAreMissing(array $missingFields): self
    {
        return new self('Error: Field/s ' . implode(', ', $missingFields) . ' is mandatory');
    }
}