<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $bookings = $this->whenLoaded('bookings', function() {
            return $this->bookings->map(function($booking) {
                return [
                    'check_in' => Carbon::parse($booking->check_in)->format('Y-m-d H:i'),
                    'check_out' => Carbon::parse($booking->check_out)->format('Y-m-d H:i')
                ];
            });
        });

        return [
            'room_number' => $this->room_number,
            'price_for_night' => $this->price_for_night,
            'price_for_day' => $this->price_for_day,
            'status' => $this->whenLoaded('status', fn () => ucfirst($this->status->name)),
            'bookings' => $bookings ?? []
        ];
    }
}
