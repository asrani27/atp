<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\GeoLocationService;

class GeoLocationServiceTest extends TestCase
{
    public function test_haversine_distance_calculation_same_point(): void
    {
        $lat = -3.441544;
        $lon = 114.843232;

        $distance = GeoLocationService::calculateDistance($lat, $lon, $lat, $lon);
        $this->assertEquals(0, $distance);
    }

    public function test_haversine_distance_within_and_outside_radius(): void
    {
        $officeLat = -3.441544;
        $officeLon = 114.843232;
        $allowedRadius = 150; // meters

        // Test 1: User is 20 meters away (approx 0.00018 deg lat)
        $nearLat = -3.441700;
        $nearLon = 114.843232;
        $checkNear = GeoLocationService::isWithinRadius($nearLat, $nearLon, $officeLat, $officeLon, $allowedRadius);
        $this->assertTrue($checkNear['is_valid']);
        $this->assertLessThanOrEqual($allowedRadius, $checkNear['distance_meters']);

        // Test 2: User is 5 kilometers away
        $farLat = -3.480000;
        $farLon = 114.843232;
        $checkFar = GeoLocationService::isWithinRadius($farLat, $farLon, $officeLat, $officeLon, $allowedRadius);
        $this->assertFalse($checkFar['is_valid']);
        $this->assertGreaterThan($allowedRadius, $checkFar['distance_meters']);
    }
}
