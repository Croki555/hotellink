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
        $start = Carbon::parse($request->input('start_date'));
        $end = Carbon::parse($request->input('end_date'));
        $perPage = $request->input('per_page', 10);

        $rooms = Room::doesntHave('bookings')
            ->orWhereHas('bookings', function($q) use ($start, $end) {
                $q->where(function($query) use ($start, $end) {
                    $query->whereBetween('check_in', [$start, $end])
                        ->orWhereBetween('check_out', [$start, $end])
                        ->orWhere(function($q) use ($start, $end) {
                            $q->where('check_in', '<=', $start)
                                ->where('check_out', '>=', $end);
                        });
                });
            }, '=', 0)
            ->orderBy('room_number', 'asc')
            ->paginate($perPage);

        $rooms->appends($request->query());

        return response()->json([
            'message' => 'Список доступных комнат за указанный период',
            'data' => RoomResource::collection($rooms),
            'meta' => [
                'current_page' => $rooms->currentPage(),
                'last_page' => $rooms->lastPage(),
                'per_page' => $rooms->perPage(),
                'total' => $rooms->total(),
            ],
            'links' => [
                'first' => $rooms->url(1),
                'last' => $rooms->url($rooms->lastPage()),
                'prev' => $rooms->previousPageUrl(),
                'next' => $rooms->nextPageUrl(),
            ],
        ], Response::HTTP_OK);
    }
}
