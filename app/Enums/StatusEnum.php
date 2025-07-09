<?php

namespace App\Enums;

enum StatusEnum: string
{
    case BOOKED = 'Забронировано';
    case UNBOOKED = 'Свободна';
    public function isAvailable(): bool
    {
        return match ($this) {
            self::BOOKED => false,
            self::UNBOOKED => true,
        };
    }
}
