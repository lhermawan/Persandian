<?php

namespace App\Services;

class MonitoringService
{
    public static function getStatus()
    {
        // Logika untuk mendapatkan status monitoring
        return [
            'status' => 'OK',
            'timestamp' => now(),
        ];
    }
}
