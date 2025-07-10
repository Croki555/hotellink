<?php

namespace App\Enums;

enum BookingStatus: string
{
    case OCCUPIED = 'OCCUPIED';
    case NOT_OCCUPIED = 'NOT_OCCUPIED';
    case CHECK_OUT = 'CHECK_OUT';
}
