<?php

declare(strict_types=1);

namespace App\Back\BookingRequest\Domain;

final class BookingRequest
{
    public function __construct(
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

    public static function fromJson(string $bookingRequests): BookingRequestList
    {
        $bookingRequestsArray = json_decode($bookingRequests, true, 512, JSON_THROW_ON_ERROR);
        $bookingRequests = [];
        foreach ($bookingRequestsArray as $bookingRequestArray) {
            self::checkBookingRequestFieldsConsistency($bookingRequestArray);
            $bookingRequests[] = BookingRequest::create(
                $bookingRequestArray[BookingRequestContract::REQUEST_ID],
                $bookingRequestArray[BookingRequestContract::CHECK_IN],
                $bookingRequestArray[BookingRequestContract::NIGHTS],
                $bookingRequestArray[BookingRequestContract::SELLING_RATE],
                $bookingRequestArray[BookingRequestContract::MARGIN],
            );
        }
        return BookingRequestList::create($bookingRequests);
    }

    /**
     * @throws ValidationErrorResponse
     */
    public static function checkBookingRequestFieldsConsistency(array $bookingRequest): void
    {
        $missingFields = [];
        $bookingRequestArrayKeys = array_keys($bookingRequest);
        foreach (BookingRequestContract::REQUIRED_JSON_FIELDS as $requiredField) {
            if (!in_array($requiredField, $bookingRequestArrayKeys, true)) {
                $missingFields[] = $requiredField;
            }
        }
        if (!empty($missingFields)) {
            throw ValidationErrorResponse::becauseRequiredFieldsAreMissing($missingFields);
        }
    }

    public function toArray(): array
    {
        return [
            BookingRequestContract::REQUEST_ID => $this->id(),
            BookingRequestContract::CHECK_IN => $this->checkIn(),
            BookingRequestContract::NIGHTS => $this->nights(),
            BookingRequestContract::SELLING_RATE => $this->sellingRate(),
            BookingRequestContract::MARGIN => $this->margin(),
        ];
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