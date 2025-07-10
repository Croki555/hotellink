<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Http\Requests\BookingRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\RoomWithBookingsResource;
use App\Models\Booking;
use App\Models\Room;
use App\Services\Booking\BookingServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingServiceInterface $bookingService
    ) {
    }

    public function index(Request $request):JsonResponse
    {
        $bookings = $this->bookingService->getAllBookings();

        return response()->json([
            'message' => 'Список броней по комнатам',
            'data' => RoomWithBookingsResource::collection($bookings),
        ], Response::HTTP_OK);
    }

    public function store(BookingRequest $request): JsonResponse
    {
        $bookingData = $this->bookingService->createBooking(
            $request->room_number,
            $request->client_id,
            Carbon::parse($request->check_in),
            Carbon::parse($request->check_out),
            $request->status
        );

        return response()->json([
            'message' => 'Бронирование создано',
            'data' => $bookingData
        ], Response::HTTP_CREATED);
    }
}
