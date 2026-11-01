<?php

namespace App\Services;

class GeoLocationService
{
    private const EARTH_RADIUS_METERS = 6371000;

    /**
     * Hitung jarak dua titik koordinat GPS menggunakan formula Haversine
     * Mengembalikan jarak dalam satuan meter.
     */
    public static function calculateDistance(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo   = deg2rad($lat2);
        $lonTo   = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
        ));

        return round($angle * self::EARTH_RADIUS_METERS, 2);
    }

    /**
     * Validasi apakah posisi pegawai berada di dalam radius toleransi kantor/lokasi
     */
    public static function isWithinRadius(
        float $userLat,
        float $userLon,
        float $targetLat,
        float $targetLon,
        int $allowedRadiusMeters
    ): array {
        $distance = self::calculateDistance($userLat, $userLon, $targetLat, $targetLon);
        
        return [
            'is_valid' => $distance <= $allowedRadiusMeters,
            'distance_meters' => $distance,
            'allowed_radius' => $allowedRadiusMeters,
            'difference' => $distance > $allowedRadiusMeters ? round($distance - $allowedRadiusMeters, 2) : 0,
        ];
    }
}
