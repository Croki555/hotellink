<?php

namespace App\Services\Booking;

use App\Enums\BookingStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

interface BookingServiceInterface
{
    public function createBooking(
        int $roomNumber,
        int $clientId,
        Carbon $checkIn,
        Carbon $checkOut,
        string $status
    ): array|JsonResponse;

    public function getAllBookings(): Collection;
}
