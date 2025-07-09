<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Support\Carbon;

class BookingObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the Booking "created" event.
     */
    public function created(Booking $booking): void
    {
        if (!is_null($booking->total_price)) {
            return;
        }

        $totalPrice = $booking->totalPriceCalc(
            Carbon::parse($booking->check_in),
            Carbon::parse($booking->check_out),
            $booking->room_id
        );

        Booking::withoutEvents(function () use ($booking, $totalPrice) {
            $booking->update(['total_price' => $totalPrice]);
        });
    }

    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "deleted" event.
     */
    public function deleted(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "restored" event.
     */
    public function restored(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "force deleted" event.
     */
    public function forceDeleted(Booking $booking): void
    {
        //
    }
}
