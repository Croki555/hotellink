<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Booking extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'client_id',
        'room_id',
        'check_in',
        'check_out',
        'total_price',
        'status_id'
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
    public function totalPriceCalc(Carbon $checkIn, Carbon $checkOut, int $roomId): float
    {
        $room = Room::findOrFail($roomId, ['id', 'price_for_night', 'price_for_day']);

        $totalDays = $checkIn->diffInDays($checkOut, false);

        if ($totalDays >= 1) {
            return $totalDays * $room->price_for_night;
        }

        // Памятка: для бронирования меньше дня, проверяю ночной период (00:00-06:00)
        $isNightBooking = ($checkIn->hour >= 0 && $checkIn->hour < 6)
            || ($checkOut->hour > 0 && $checkOut->hour <= 6);

        return $isNightBooking ? $room->price_for_night : $room->price_for_day;
    }
}
