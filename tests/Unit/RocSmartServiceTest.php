<?php

namespace Tests\Unit;

use App\Services\RocSmartService;
use PHPUnit\Framework\TestCase;

class RocSmartServiceTest extends TestCase
{
    private RocSmartService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new RocSmartService();
    }

    /**
     * Test 1: Verifikasi Perhitungan Bobot ROC
     *
     * Contoh Manual (m = 3):
     * C1 (Rank 1) = (1/3) * (1/1 + 1/2 + 1/3) = 11/18 = ~0.611111
     * C2 (Rank 2) = (1/3) * (1/2 + 1/3)       = 5/18  = ~0.277778
     * C3 (Rank 3) = (1/3) * (1/3)             = 2/18  = ~0.111111
     * Total Bobot = 1.0
     */
    public function test_perhitungan_bobot_roc_berhasil(): void
    {
        $orderedKriteria = ['C1', 'C2', 'C3'];

        $weights = $this->service->calculateRocWeights($orderedKriteria);

        // Verifikasi persentase/bobot
        $this->assertEqualsWithDelta(11 / 18, $weights['C1'], 0.0001, 'Bobot C1 harus ~0.6111');
        $this->assertEqualsWithDelta(5 / 18, $weights['C2'], 0.0001, 'Bobot C2 harus ~0.2778');
        $this->assertEqualsWithDelta(2 / 18, $weights['C3'], 0.0001, 'Bobot C3 harus ~0.1111');

        // Total jumlah bobot harus 1.0
        $totalWeight = array_sum($weights);
        $this->assertEqualsWithDelta(1.0, $totalWeight, 0.0001, 'Total bobot ROC harus 1.0');
    }

    /**
     * Test 2: Verifikasi Perhitungan Utility SMART
     *
     * C1 (Cost): Min = 50, Max = 80
     * - Toko A (Val = 50) -> Utility = (80 - 50) / (80 - 50) = 1.0
     * - Toko B (Val = 80) -> Utility = (80 - 80) / (80 - 50) = 0.0
     *
     * C2 (Benefit): Min = 80, Max = 90
     * - Toko A (Val = 80) -> Utility = (80 - 80) / (90 - 80) = 0.0
     * - Toko B (Val = 90) -> Utility = (90 - 80) / (90 - 80) = 1.0
     */
    public function test_perhitungan_utility_smart_berhasil(): void
    {
        // Cost
        $uA_Cost = $this->service->calculateUtility(50, 50, 80, 'cost');
        $uB_Cost = $this->service->calculateUtility(80, 50, 80, 'cost');

        $this->assertEquals(1.0, $uA_Cost, 'Utility Cost Toko A harus 1.0');
        $this->assertEquals(0.0, $uB_Cost, 'Utility Cost Toko B harus 0.0');

        // Benefit
        $uA_Benefit = $this->service->calculateUtility(80, 80, 90, 'benefit');
        $uB_Benefit = $this->service->calculateUtility(90, 80, 90, 'benefit');

        $this->assertEquals(0.0, $uA_Benefit, 'Utility Benefit Toko A harus 0.0');
        $this->assertEquals(1.0, $uB_Benefit, 'Utility Benefit Toko B harus 1.0');
    }

    /**
     * Test 3: Verifikasi Perhitungan Skor Akhir dan Ranking Toko
     *
     * Perhitungan manual:
     * Toko A: (1.0 * 11/18) + (0.0 * 5/18) + (1.0 * 2/18) = 13/18 = ~0.7222
     * Toko B: (0.0 * 11/18) + (1.0 * 5/18) + (0.0 * 2/18) = 5/18  = ~0.2778
     *
     * Urutan Ranking: Toko A (Peringkat 1) -> Toko B (Peringkat 2)
     */
    public function test_proses_lengkap_roc_smart_dan_ranking_berhasil(): void
    {
        $orderedKriteria = ['C1', 'C2', 'C3'];

        $kriteriaTypes = [
            'C1' => 'cost',
            'C2' => 'benefit',
            'C3' => 'benefit',
        ];

        $alternativesValues = [
            'Toko A' => ['C1' => 50, 'C2' => 80, 'C3' => 90],
            'Toko B' => ['C1' => 80, 'C2' => 90, 'C3' => 70],
        ];

        $result = $this->service->processRocSmart($orderedKriteria, $alternativesValues, $kriteriaTypes);

        $rankings = $result['rankings'];

        // Harus ada 2 toko di hasil ranking
        $this->assertCount(2, $rankings);

        // Peringkat 1 harus Toko A dengan skor ~0.7222 (13/18)
        $this->assertEquals('Toko A', $rankings[0]['toko_id']);
        $this->assertEqualsWithDelta(13 / 18, $rankings[0]['score'], 0.0001);

        // Peringkat 2 harus Toko B dengan skor ~0.2778 (5/18)
        $this->assertEquals('Toko B', $rankings[1]['toko_id']);
        $this->assertEqualsWithDelta(5 / 18, $rankings[1]['score'], 0.0001);
    }
}
