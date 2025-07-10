<?php

namespace App\Http\Requests;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'room_number' => ['required', 'integer', 'exists:rooms,room_number'],
            'check_in' => [
                'required',
                'date_format:Y-m-d H:i',
                function ($attribute, $value, $fail) {
                    $timezone = config('app.timezone');
                    $checkIn = Carbon::parse($value, $timezone);
                    $now = Carbon::now($timezone);

                    if ($checkIn->isPast() && !$checkIn->isSameDay($now)) {
                        $fail('Нельзя указать прошедшую дату для заезда');
                    }

                    if ($checkIn->isToday() && $checkIn->diffInHours($now) < 1) {
                        $minTime = $now->copy()->addHour()->format('H:i');
                        $fail("При бронировании на сегодня минимальное время заезда - {$minTime}");
                    }
                },
            ],
            'check_out' => [
                'required',
                'date_format:Y-m-d H:i',
                'after:check_in',
                function ($attribute, $value, $fail) {
                    $timezone = config('app.timezone');
                    $minHours = Booking::MIN_HOURS;
                    $start = Carbon::parse(request('check_in'), $timezone);
                    $end = Carbon::parse($value, $timezone);

                    if ($end->diffInHours($start) < $minHours) {
                        $fail("Минимальная длительность бронирования — {$minHours} часов.");
                    }
                },
            ],
            'status' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $upperValue = strtoupper($value);

                    if (!in_array($upperValue, array_column(BookingStatus::cases(), 'value'))) {
                        $availableStatuses = array_map(
                            fn(BookingStatus $status) => strtolower($status->value),
                            BookingStatus::cases()
                        );

                        $fail("Недопустимый статус. Доступные статусы: " . implode(', ', $availableStatuses));
                    }
                },
            ]
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $timezone = config('app.timezone');
                $roomNumber = $this->input('room_number');
                $checkIn = Carbon::parse($this->input('check_in'), $timezone);

                $overlappingBooking = Booking::whereHas('room', function ($query) use ($roomNumber) {
                    $query->where('room_number', $roomNumber);
                })->where(function ($query) use ($checkIn) {
                        $query->where('check_out', '>', $checkIn);
                })->orderBy('check_out', 'desc')->first();

                if ($overlappingBooking) {
                    $bookingEnd = Carbon::parse($overlappingBooking->check_out, $timezone);
                    $nextAvailable = $bookingEnd->copy()->addHour();

                    if ($checkIn->lt($bookingEnd)) {
                        $validator->errors()->add(
                            'room_number',
                            'Комната занята до ' . $bookingEnd->format('Y-m-d H:i') . '. ' .
                            'Ближайшее доступное время: ' . $nextAvailable->format('Y-m-d H:i')
                        );
                    }
                }
            }
        ];
    }
}
