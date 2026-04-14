<?php

namespace App\Helpers;

class TimeSlotHelper
{
    public const SLOTS = [
        'A' => ['start' => '07:00', 'end' => '08:00'],
        'B' => ['start' => '08:00', 'end' => '09:00'],
        'C' => ['start' => '09:00', 'end' => '10:00'],
        'D' => ['start' => '10:00', 'end' => '11:00'],
        'E' => ['start' => '11:00', 'end' => '12:00'],
        'F' => ['start' => '12:00', 'end' => '13:00'],
        'G' => ['start' => '13:00', 'end' => '14:00'],
        'H' => ['start' => '14:00', 'end' => '15:00'],
        'I' => ['start' => '15:00', 'end' => '16:00'],
        'J' => ['start' => '16:00', 'end' => '17:00'],
        'K' => ['start' => '17:00', 'end' => '18:00'],
        'L' => ['start' => '18:00', 'end' => '19:00'],
        'M' => ['start' => '19:00', 'end' => '20:00'],
        'N' => ['start' => '20:00', 'end' => '21:00'],
        'O' => ['start' => '21:00', 'end' => '22:00'],
    ];

    /**
     * Get the start and end times for a specific slot character.
     *
     * @param string $slot
     * @return array|null
     */
    public static function getSlotTimes(string $slot): ?array
    {
        return self::SLOTS[strtoupper($slot)] ?? null;
    }
}
