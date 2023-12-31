<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Domain;

use App\Back\BookingRequest\Application\ValidationErrorResponse;
use App\Back\BookingRequest\Domain\ValueObjects\BookingRequestCheckIn;
use App\Back\BookingRequest\Domain\ValueObjects\BookingRequestId;
use App\Back\BookingRequest\Domain\ValueObjects\BookingRequestMargin;
use App\Back\BookingRequest\Domain\ValueObjects\BookingRequestNumberOfNights;
use App\Back\BookingRequest\Domain\ValueObjects\BookingRequestSellingRate;

final class BookingRequest
{
    private function __construct(
        private readonly BookingRequestId $id,
        private readonly BookingRequestCheckIn $checkIn,
        private readonly BookingRequestNumberOfNights $nights,
        private readonly BookingRequestSellingRate $sellingRate,
        private readonly BookingRequestMargin $margin
    )
    {
    }

    public static function create(
        string $id,
        string $checkIn,
        int    $nights,
        int    $sellingRate,
        int    $margin
    ): BookingRequest
    {
        return new self(
            new BookingRequestId($id),
            new BookingRequestCheckIn($checkIn),
            new BookingRequestNumberOfNights($nights),
            new BookingRequestSellingRate($sellingRate),
            new BookingRequestMargin($margin)
        );
    }

    public function toArray(): array
    {
        return [
            BookingRequestContract::request_id() => $this->id(),
            BookingRequestContract::check_in() => $this->checkIn(),
            BookingRequestContract::nights() => $this->nights(),
            BookingRequestContract::selling_rate() => $this->sellingRate(),
            BookingRequestContract::margin() => $this->margin(),
        ];
    }

    /**
     * @throws ValidationErrorResponse
     */
    public static function checkBookingRequestFieldsConsistency(array $bookingRequest): void
    {
        $missingFields = [];
        $bookingRequestArrayKeys = array_keys($bookingRequest);
        foreach (BookingRequestContract::required_json_fields() as $requiredField) {
            if (!in_array($requiredField, $bookingRequestArrayKeys, true)) {
                $missingFields[] = $requiredField;
            }
        }
        if (!empty($missingFields)) {
            throw ValidationErrorResponse::becauseRequiredFieldsAreMissing($missingFields);
        }
    }

    public static function hasDifferentIds(mixed $firstBooking, mixed $secondBooking): bool
    {
        return $firstBooking[BookingRequestContract::request_id()] !== $secondBooking[BookingRequestContract::request_id()];
    }

    public static function hasConflictiveDates(array $booking, array $bookingToCompare): bool
    {
        $checkIn = strtotime($booking[BookingRequestContract::check_in()]);
        $checkOut = strtotime("+" . $booking[BookingRequestContract::nights()] . " days", $checkIn);

        $checkInToCompare = strtotime($bookingToCompare[BookingRequestContract::check_in()]);
        $checkOutToCompare = strtotime("+" . $bookingToCompare[BookingRequestContract::nights()] . " days", $checkInToCompare);

        return ($checkIn < $checkOutToCompare && $checkOut > $checkInToCompare);
    }

    public static function calculateProfit(float $sellingRate, float $margin): float
    {
        return ($sellingRate * $margin) / 100;
    }

    public function profitPerNight(): float
    {
        return $this->sellingRate() * ($this->margin() / 100) / $this->nights();
    }

    public function nights(): int
    {
        return $this->nights->value();
    }

    public function sellingRate(): int
    {
        return $this->sellingRate->value();
    }

    public function margin(): int
    {
        return $this->margin->value();
    }

    public function id(): string
    {
        return $this->id->value();
    }

    public function checkIn(): string
    {
        return $this->checkIn->value();
    }
}