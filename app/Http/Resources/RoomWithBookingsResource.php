<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomWithBookingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'room_number' => $this->room_number,
            'bookings' => $this->whenLoaded('bookings', fn() => $this->bookings->map(fn($booking) => [
                'check_in' => Carbon::parse($booking->check_in)->format('Y-m-d H:i'),
                'check_out' => Carbon::parse($booking->check_out)->format('Y-m-d H:i'),
                'status' => __('status.' . strtolower($booking->status)),
                'total_price' => $booking->total_price,
            ])),
        ];
    }
}
