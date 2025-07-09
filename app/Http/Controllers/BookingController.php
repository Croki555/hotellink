<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends Controller
{
    public function store(BookingRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $room = Room::where('room_number', $request->room_number)
                ->firstOrFail(['id']);

            Booking::create([
                'client_id' => $request->client_id,
                'room_id' => $room->id,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'status_id' => 2,
                'total_price' => null
            ]);

            $room->status_id = 1;
            $room->save();

            DB::commit();

            return response()->json([
                'message' => 'Бронирование создано',
            ], Response::HTTP_CREATED);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Ошибка при создании бронирования',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
