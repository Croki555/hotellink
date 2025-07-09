<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomRequest;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class RoomController extends Controller
{
    public function __invoke(RoomRequest $request): JsonResponse
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Room::with(['status', 'bookings']);

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);

            $query->whereDoesntHave('bookings', function($q) use ($start, $end) {
                $q->where(function($query) use ($start, $end) {
                    $query->whereBetween('check_in', [$start, $end])
                        ->orWhereBetween('check_out', [$start, $end])
                        ->orWhere(function($q) use ($start, $end) {
                            $q->where('check_in', '<=', $start)
                                ->where('check_out', '>=', $end);
                        });
                });
            });
        }

        $rooms = $query->get();

        return response()->json([
            'message' => $startDate && $endDate ? 'Список свободных комнат' : 'Список всех комнат',
            'data' => RoomResource::collection($rooms),
        ], Response::HTTP_OK);
    }
}
