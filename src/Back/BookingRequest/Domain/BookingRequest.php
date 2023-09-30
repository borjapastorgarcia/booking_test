<?php

declare(strict_types=1);


namespace App\Back\BookingRequest\Domain;

use JsonException;

final class BookingRequest
{
    private const REQUIRED_JSON_FIELDS = [
        'request_id',
        'check_in',
        'nights',
        'selling_rate',
        'margin',
    ];

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

    /**
     * @throws ValidationErrorResponse
     * @throws JsonException
     */
    public static function fromJson(string $bookingRequests): BookingRequestList
    {
        $bookingRequestsArray = json_decode($bookingRequests, true, 512, JSON_THROW_ON_ERROR);
        $bookingRequests = [];
        foreach ($bookingRequestsArray as $bookingRequestArray) {
            self::checkBookingRequestFieldsConsistency($bookingRequestArray);
            $bookingRequests[] = BookingRequest::create(
                $bookingRequestArray['request_id'],
                $bookingRequestArray['check_in'],
                $bookingRequestArray['nights'],
                $bookingRequestArray['selling_rate'],
                $bookingRequestArray['margin'],
            );
        }
        return new BookingRequestList($bookingRequests);
    }

    /**
     * @throws ValidationErrorResponse
     */
    private static function checkBookingRequestFieldsConsistency(array $bookingRequest): void
    {
        $missingFields = [];
        $bookingRequestArrayKeys = array_keys($bookingRequest);

        foreach (self::REQUIRED_JSON_FIELDS as $requiredField) {
            if (!in_array($requiredField, $bookingRequestArrayKeys, true)) {
                $missingFields[] = $requiredField;
            }
        }
        if (!empty($missingFields)) {
            throw ValidationErrorResponse::becauseRequiredFieldsAreMissing($missingFields);
        }
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

}