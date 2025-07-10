<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Booking extends Model
{
    use HasFactory;

    const MIN_HOURS = 6;

    protected $fillable = [
        'client_id',
        'room_id',
        'check_in',
        'check_out',
        'total_price',
        'status'
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
    public static function totalPriceCalc(Carbon $checkIn, Carbon $checkOut, int $roomPrice): float
    {
        $totalDays = $checkIn->diffInDays($checkOut, false);

        if ($totalDays >= 1) {
            return $totalDays * $roomPrice;
        }else {
            return $roomPrice;
        }
    }
}
