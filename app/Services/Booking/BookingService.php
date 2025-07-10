<?php

namespace App\Services\Booking;

use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class BookingService implements BookingServiceInterface
{

    public function createBooking(int $roomNumber, int $clientId, Carbon $checkIn, Carbon $checkOut, string $status): array|JsonResponse
    {
        DB::beginTransaction();

        try {
            $room = Room::where('room_number', $roomNumber)
                ->firstOrFail(['id', 'price_for_night', 'room_number']);

            $dataBooking = [
                'client_id' => $clientId,
                'room_id' => $room->id,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'status' => strtolower($status),
            ];

            $dataBooking['total_price'] = Booking::totalPriceCalc(
                $checkIn,
                $checkOut,
                $room->price_for_night
            );

            $booking = Booking::create($dataBooking);

            DB::commit();

            return [
                'room_number' => $room->room_number,
                'total_price' => $booking->total_price,
                'check_in' => $booking->check_in->format('Y-m-d H:i'),
                'check_out' => $booking->check_out->format('Y-m-d H:i'),
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Booking creation error: ' . $e->getMessage(), [
                'exception' => $e,
                'roomNumber' => $roomNumber,
                'clientId' => $clientId
            ]);

            return response()->json([
                'message' => 'Ошибка при создании бронирования',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllBookings(): Collection
    {
        return Room::with(['bookings'])->has('bookings')
            ->orderBy('room_number')
            ->get();
    }
}
