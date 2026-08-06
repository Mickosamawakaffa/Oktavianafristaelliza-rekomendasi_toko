<?php

namespace Tests\Unit;

use App\Services\RocSmartService;
use PHPUnit\Framework\TestCase;

class HaversineDistanceTest extends TestCase
{
    /**
     * Test Haversine formula distance calculation between two coordinates in Jember.
     */
    public function test_haversine_distance_calculation_is_accurate(): void
    {
        $service = new RocSmartService();

        // Alun-alun Jember: (-8.1724, 113.7000)
        // UNEJ (Universitas Jember): (-8.1630, 113.7170)
        // Distance should be approximately 2.1 KM
        $distance = $service->calculateHaversineDistance(-8.1724, 113.7000, -8.1630, 113.7170);

        $this->assertGreaterThan(1.5, $distance);
        $this->assertLessThan(3.0, $distance);
    }

    /**
     * Test distance to same point is 0 KM.
     */
    public function test_distance_to_same_point_is_zero(): void
    {
        $service = new RocSmartService();
        $distance = $service->calculateHaversineDistance(-8.1695, 113.7152, -8.1695, 113.7152);

        $this->assertEquals(0.0, $distance);
    }
}
