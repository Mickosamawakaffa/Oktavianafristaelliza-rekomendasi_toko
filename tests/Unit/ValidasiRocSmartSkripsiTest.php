<?php

/**
 * ============================================================
 * VALIDASI IMPLEMENTASI ROC-SMART  ·  Bahan Laporan Skripsi
 * ============================================================
 *
 * Tujuan  : Membuktikan bahwa implementasi rumus ROC dan SMART
 *           pada RocSmartService.php menghasilkan nilai yang
 *           identik dengan perhitungan manual (selisih ≤ 0.0001).
 *
 * Metode  : PHPUnit (php artisan test --filter ValidasiRocSmart)
 *
 * Data Uji:
 *   - n = 6 kriteria
 *   - Urutan prioritas (rank 1 = paling penting):
 *       1. Kualitas Produk  (benefit)
 *       2. Kelengkapan Produk (benefit)
 *       3. Variasi Barang    (benefit)
 *       4. Harga             (cost)
 *       5. Lokasi            (benefit)
 *       6. Ulasan            (benefit)
 *   - 3 alternatif toko: Toko A, Toko B, Toko C
 * ============================================================
 */

namespace Tests\Unit;

use App\Services\RocSmartService;
use PHPUnit\Framework\TestCase;

class ValidasiRocSmartSkripsiTest extends TestCase
{
    // ─── Konstanta ID Kriteria ──────────────────────────────────────────────
    private const K_KUALITAS     = 'kualitas';      // rank 1 – benefit
    private const K_KELENGKAPAN  = 'kelengkapan';   // rank 2 – benefit
    private const K_VARIASI      = 'variasi';       // rank 3 – benefit
    private const K_HARGA        = 'harga';         // rank 4 – cost
    private const K_LOKASI       = 'lokasi';        // rank 5 – benefit
    private const K_ULASAN       = 'ulasan';        // rank 6 – benefit

    private const DELTA = 0.0001;   // toleransi pembulatan

    private RocSmartService $service;

    // ─── Urutan prioritas (index 0 = rank tertinggi) ───────────────────────
    private array $orderedKriteria = [
        self::K_KUALITAS,
        self::K_KELENGKAPAN,
        self::K_VARIASI,
        self::K_HARGA,
        self::K_LOKASI,
        self::K_ULASAN,
    ];

    // ─── Jenis tiap kriteria ───────────────────────────────────────────────
    private array $kriteriaTypes = [
        self::K_KUALITAS    => 'benefit',
        self::K_KELENGKAPAN => 'benefit',
        self::K_VARIASI     => 'benefit',
        self::K_HARGA       => 'cost',
        self::K_LOKASI      => 'benefit',
        self::K_ULASAN      => 'benefit',
    ];

    // ─── Nilai kriteria tiap toko (skala 1–100) ────────────────────────────
    private array $alternativesValues = [
        'Toko A' => [
            self::K_KUALITAS    => 80,
            self::K_KELENGKAPAN => 85,
            self::K_VARIASI     => 70,
            self::K_HARGA       => 75,
            self::K_LOKASI      => 60,
            self::K_ULASAN      => 65,
        ],
        'Toko B' => [
            self::K_KUALITAS    => 78,
            self::K_KELENGKAPAN => 80,
            self::K_VARIASI     => 75,
            self::K_HARGA       => 60,
            self::K_LOKASI      => 70,
            self::K_ULASAN      => 72,
        ],
        'Toko C' => [
            self::K_KUALITAS    => 88,
            self::K_KELENGKAPAN => 82,
            self::K_VARIASI     => 68,
            self::K_HARGA       => 75,
            self::K_LOKASI      => 65,
            self::K_ULASAN      => 70,
        ],
    ];

    // ──────────────────────────────────────────────────────────────────────
    //  PERHITUNGAN MANUAL (ground truth)
    // ──────────────────────────────────────────────────────────────────────
    //
    //  ROC WEIGHTS  (n = 6)
    //  Formula: w_k = (1/n) x Sigma(1/i), i = k..n
    //
    //  k=1 (Kualitas)    : (1/6)x(1/1+1/2+1/3+1/4+1/5+1/6)
    //                    = (1/6)x(147/60) = 147/360 = 0.408333
    //
    //  k=2 (Kelengkapan) : (1/6)x(1/2+1/3+1/4+1/5+1/6)
    //                    = (1/6)x(87/60) = 87/360  = 0.241667
    //
    //  k=3 (Variasi)     : (1/6)x(1/3+1/4+1/5+1/6)
    //                    = (1/6)x(57/60) = 57/360  = 0.158333
    //
    //  k=4 (Harga)       : (1/6)x(1/4+1/5+1/6)
    //                    = (1/6)x(37/60) = 37/360  = 0.102778
    //
    //  k=5 (Lokasi)      : (1/6)x(1/5+1/6)
    //                    = (1/6)x(22/60) = 22/360  = 0.061111
    //
    //  k=6 (Ulasan)      : (1/6)x(1/6)
    //                    = (1/6)x(10/60) = 10/360  = 0.027778
    //
    //  Total             = 360/360 = 1.000000  (valid)
    //
    // ──────────────────────────────────────────────────────────────────────
    //  MIN / MAX per kriteria dari 3 toko:
    //   Kualitas    : min=78, max=88
    //   Kelengkapan : min=80, max=85
    //   Variasi     : min=68, max=75
    //   Harga       : min=60, max=75
    //   Lokasi      : min=60, max=70
    //   Ulasan      : min=65, max=72
    //
    //  UTILITY (hasil 0.0 - 1.0):
    //
    //  Toko A:
    //   Kualitas    (benefit): (80-78)/(88-78) = 2/10   = 0.200000
    //   Kelengkapan (benefit): (85-80)/(85-80) = 5/5    = 1.000000
    //   Variasi     (benefit): (70-68)/(75-68) = 2/7    = 0.285714
    //   Harga       (cost)  : (75-75)/(75-60) = 0/15   = 0.000000
    //   Lokasi      (benefit): (60-60)/(70-60) = 0/10   = 0.000000
    //   Ulasan      (benefit): (65-65)/(72-65) = 0/7    = 0.000000
    //
    //  Toko B:
    //   Kualitas    (benefit): (78-78)/(88-78) = 0/10   = 0.000000
    //   Kelengkapan (benefit): (80-80)/(85-80) = 0/5    = 0.000000
    //   Variasi     (benefit): (75-68)/(75-68) = 7/7    = 1.000000
    //   Harga       (cost)  : (75-60)/(75-60) = 15/15  = 1.000000
    //   Lokasi      (benefit): (70-60)/(70-60) = 10/10  = 1.000000
    //   Ulasan      (benefit): (72-65)/(72-65) = 7/7    = 1.000000
    //
    //  Toko C:
    //   Kualitas    (benefit): (88-78)/(88-78) = 10/10  = 1.000000
    //   Kelengkapan (benefit): (82-80)/(85-80) = 2/5    = 0.400000
    //   Variasi     (benefit): (68-68)/(75-68) = 0/7    = 0.000000
    //   Harga       (cost)  : (75-75)/(75-60) = 0/15   = 0.000000
    //   Lokasi      (benefit): (65-60)/(70-60) = 5/10   = 0.500000
    //   Ulasan      (benefit): (70-65)/(72-65) = 5/7    = 0.714286
    //
    //  SKOR AKHIR = Sigma(bobot x utility):
    //
    //   Toko A:
    //     = (147/360 x 0.2) + (87/360 x 1.0) + (57/360 x 2/7)
    //       + (37/360 x 0.0) + (22/360 x 0.0) + (10/360 x 0.0)
    //     = 29.4/360 + 87/360 + 114/2520
    //     ~= 0.368572
    //
    //   Toko B:
    //     = (147/360 x 0.0) + (87/360 x 0.0) + (57/360 x 1.0)
    //       + (37/360 x 1.0) + (22/360 x 1.0) + (10/360 x 1.0)
    //     = 0 + 0 + 57/360 + 37/360 + 22/360 + 10/360
    //     = 126/360 = 0.350000
    //
    //   Toko C:
    //     = (147/360 x 1.0) + (87/360 x 0.4) + (57/360 x 0.0)
    //       + (37/360 x 0.0) + (22/360 x 0.5) + (10/360 x 5/7)
    //     = 147/360 + 34.8/360 + 11/360 + 50/2520
    //     ~= 0.555397
    //
    //  RANKING (tertinggi ke terendah):
    //   1. Toko C ~= 0.555397
    //   2. Toko A ~= 0.368572
    //   3. Toko B  = 0.350000
    // ──────────────────────────────────────────────────────────────────────

    /** Bobot ROC manual (nilai exact fraksional) */
    private array $expectedWeights = [
        self::K_KUALITAS    => 147 / 360,  // ~= 0.408333
        self::K_KELENGKAPAN =>  87 / 360,  // ~= 0.241667
        self::K_VARIASI     =>  57 / 360,  // ~= 0.158333
        self::K_HARGA       =>  37 / 360,  // ~= 0.102778
        self::K_LOKASI      =>  22 / 360,  // ~= 0.061111
        self::K_ULASAN      =>  10 / 360,  // ~= 0.027778
    ];

    /** Utility manual tiap toko x kriteria */
    private array $expectedUtilities = [
        'Toko A' => [
            self::K_KUALITAS    =>  2 / 10,
            self::K_KELENGKAPAN =>  5 / 5,
            self::K_VARIASI     =>  2 / 7,
            self::K_HARGA       =>  0 / 15,
            self::K_LOKASI      =>  0 / 10,
            self::K_ULASAN      =>  0 / 7,
        ],
        'Toko B' => [
            self::K_KUALITAS    =>  0 / 10,
            self::K_KELENGKAPAN =>  0 / 5,
            self::K_VARIASI     =>  7 / 7,
            self::K_HARGA       => 15 / 15,
            self::K_LOKASI      => 10 / 10,
            self::K_ULASAN      =>  7 / 7,
        ],
        'Toko C' => [
            self::K_KUALITAS    => 10 / 10,
            self::K_KELENGKAPAN =>  2 / 5,
            self::K_VARIASI     =>  0 / 7,
            self::K_HARGA       =>  0 / 15,
            self::K_LOKASI      =>  5 / 10,
            self::K_ULASAN      =>  5 / 7,
        ],
    ];

    // ── setup ───────────────────────────────────────────────────────────────
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new RocSmartService();
    }

    // ════════════════════════════════════════════════════════════════════════
    //  TEST 1 – Bobot ROC (6 kriteria)
    // ════════════════════════════════════════════════════════════════════════

    /**
     * @test
     * Memvalidasi bahwa calculateRocWeights() menghasilkan bobot ROC
     * yang identik dengan perhitungan manual untuk 6 kriteria.
     */
    public function test_validasi_bobot_roc_6_kriteria(): void
    {
        $weights = $this->service->calculateRocWeights($this->orderedKriteria);

        $this->printSectionHeader('TEST 1 - BOBOT ROC (n = 6 Kriteria)');
        $this->printRocWeightsTable($weights);

        foreach ($this->expectedWeights as $kriteriaId => $expectedBobot) {
            $this->assertEqualsWithDelta(
                $expectedBobot,
                $weights[$kriteriaId],
                self::DELTA,
                "Bobot ROC [{$kriteriaId}] tidak sesuai perhitungan manual."
            );
        }

        // Total bobot harus = 1.0
        $totalBobot = array_sum($weights);
        $this->assertEqualsWithDelta(
            1.0,
            $totalBobot,
            self::DELTA,
            'Total semua bobot ROC harus sama dengan 1.0'
        );

        echo "\n  [OK] Total Bobot = " . number_format($totalBobot, 6) . " (expected 1.000000)\n";
        echo "  [OK] Semua bobot ROC valid - selisih <= " . self::DELTA . "\n\n";
    }

    // ════════════════════════════════════════════════════════════════════════
    //  TEST 2 – Utility SMART per toko
    // ════════════════════════════════════════════════════════════════════════

    /**
     * @test
     * Memvalidasi bahwa calculateUtilitiesForMatrix() menghasilkan nilai
     * utility yang identik dengan perhitungan manual untuk 3 toko & 6 kriteria.
     */
    public function test_validasi_utility_smart_3_toko(): void
    {
        $utilities = $this->service->calculateUtilitiesForMatrix(
            $this->alternativesValues,
            $this->kriteriaTypes
        );

        $this->printSectionHeader('TEST 2 - MATRIKS UTILITY SMART (3 Toko x 6 Kriteria)');
        $this->printUtilityTable($utilities);

        foreach ($this->expectedUtilities as $tokoId => $kriteriaUtils) {
            foreach ($kriteriaUtils as $kriteriaId => $expectedUtil) {
                $actualUtil = $utilities[$tokoId][$kriteriaId] ?? null;

                $this->assertNotNull(
                    $actualUtil,
                    "Utility [{$tokoId}][{$kriteriaId}] tidak ditemukan di hasil sistem."
                );
                $this->assertEqualsWithDelta(
                    $expectedUtil,
                    $actualUtil,
                    self::DELTA,
                    "Utility [{$tokoId}][{$kriteriaId}] tidak sesuai. "
                        . "Expected: " . number_format($expectedUtil, 6)
                        . ", Got: "    . number_format($actualUtil,  6)
                );
            }
        }

        echo "\n  [OK] Semua nilai utility valid - selisih <= " . self::DELTA . "\n\n";
    }

    // ════════════════════════════════════════════════════════════════════════
    //  TEST 3 – Skor Akhir & Ranking
    // ════════════════════════════════════════════════════════════════════════

    /**
     * @test
     * Memvalidasi bahwa processRocSmart() menghasilkan skor akhir dan
     * ranking toko yang identik dengan perhitungan manual penuh.
     */
    public function test_validasi_skor_akhir_dan_ranking(): void
    {
        // Hitung skor manual dari bobot x utility expected
        $manualScores = $this->hitungSkorManual();

        // Panggil sistem
        $result = $this->service->processRocSmart(
            $this->orderedKriteria,
            $this->alternativesValues,
            $this->kriteriaTypes
        );

        $this->printSectionHeader('TEST 3 - SKOR AKHIR & RANKING (Sistem vs Manual)');
        $this->printFinalScoresTable($result['rankings'], $manualScores);

        // Validasi skor tiap toko
        $rankingByToko = [];
        foreach ($result['rankings'] as $idx => $entry) {
            $rankingByToko[$entry['toko_id']] = ['score' => $entry['score'], 'rank' => $idx + 1];
        }

        foreach ($manualScores as $tokoId => $manualScore) {
            $sistemScore = $rankingByToko[$tokoId]['score'] ?? null;

            $this->assertNotNull(
                $sistemScore,
                "Toko [{$tokoId}] tidak ditemukan di hasil ranking sistem."
            );
            $this->assertEqualsWithDelta(
                $manualScore,
                $sistemScore,
                self::DELTA,
                "Skor akhir [{$tokoId}] tidak sesuai. "
                    . "Manual: " . number_format($manualScore,  6)
                    . ", Sistem: " . number_format($sistemScore, 6)
            );
        }

        // Validasi urutan ranking (Toko C > Toko A > Toko B)
        $rankings = $result['rankings'];
        $this->assertCount(3, $rankings, 'Harus ada tepat 3 toko dalam ranking.');
        $this->assertEquals('Toko C', $rankings[0]['toko_id'], 'Peringkat 1 harus Toko C');
        $this->assertEquals('Toko A', $rankings[1]['toko_id'], 'Peringkat 2 harus Toko A');
        $this->assertEquals('Toko B', $rankings[2]['toko_id'], 'Peringkat 3 harus Toko B');

        echo "\n  [OK] Urutan Ranking : 1.Toko C -> 2.Toko A -> 3.Toko B\n";
        echo "  [OK] Semua skor akhir valid - selisih <= " . self::DELTA . "\n\n";
    }

    // ════════════════════════════════════════════════════════════════════════
    //  TEST 4 – Validasi Komprehensif End-to-End
    // ════════════════════════════════════════════════════════════════════════

    /**
     * @test
     * Menjalankan seluruh pipeline ROC-SMART sekaligus dan mencetak
     * laporan lengkap (cocok untuk screenshot ke Bab 4 skripsi).
     */
    public function test_laporan_validasi_lengkap_roc_smart(): void
    {
        $result = $this->service->processRocSmart(
            $this->orderedKriteria,
            $this->alternativesValues,
            $this->kriteriaTypes
        );

        $this->printSectionHeader('LAPORAN VALIDASI LENGKAP ROC-SMART (Bab 4 Skripsi)');
        $this->printFullReport($result);

        // Pastikan pipeline tidak kosong
        $this->assertNotEmpty($result['weights'],   'Bobot ROC tidak boleh kosong.');
        $this->assertNotEmpty($result['utilities'], 'Matriks utility tidak boleh kosong.');
        $this->assertNotEmpty($result['rankings'],  'Hasil ranking tidak boleh kosong.');
        $this->assertCount(3, $result['rankings'],  'Harus ada 3 toko dalam ranking.');

        // Validasi total bobot = 1
        $this->assertEqualsWithDelta(
            1.0,
            array_sum($result['weights']),
            self::DELTA,
            'Jumlah seluruh bobot ROC harus 1.0'
        );

        echo "\n  [OK] Pipeline ROC-SMART berjalan sempurna.\n";
        echo "  [OK] Laporan siap untuk Bab 4 Skripsi.\n\n";
    }

    // ════════════════════════════════════════════════════════════════════════
    //  HELPER – Hitung skor manual dari expected weights & utilities
    // ════════════════════════════════════════════════════════════════════════

    private function hitungSkorManual(): array
    {
        $scores = [];
        foreach ($this->expectedUtilities as $tokoId => $kriteriaUtils) {
            $total = 0.0;
            foreach ($kriteriaUtils as $kriteriaId => $util) {
                $total += ($this->expectedWeights[$kriteriaId] * $util);
            }
            $scores[$tokoId] = $total;
        }
        return $scores;
    }

    // ════════════════════════════════════════════════════════════════════════
    //  HELPER – Print / Output Tabel
    // ════════════════════════════════════════════════════════════════════════

    private function printSectionHeader(string $title): void
    {
        $line = str_repeat('=', 72);
        echo "\n{$line}\n  {$title}\n{$line}\n";
    }

    private function printRocWeightsTable(array $systemWeights): void
    {
        $labels = [
            self::K_KUALITAS    => 'Kualitas Produk',
            self::K_KELENGKAPAN => 'Kelengkapan Produk',
            self::K_VARIASI     => 'Variasi Barang',
            self::K_HARGA       => 'Harga',
            self::K_LOKASI      => 'Lokasi',
            self::K_ULASAN      => 'Ulasan',
        ];

        echo "\n";
        echo "  +------+--------------------+-------+----------------+----------------+----------+\n";
        echo "  | Rank | Kriteria           | Jenis |  Bobot Manual  |  Bobot Sistem  | Selisih  |\n";
        echo "  +------+--------------------+-------+----------------+----------------+----------+\n";

        $rank = 1;
        foreach ($this->orderedKriteria as $kriteriaId) {
            $label   = $labels[$kriteriaId] ?? $kriteriaId;
            $jenis   = $this->kriteriaTypes[$kriteriaId];
            $manual  = $this->expectedWeights[$kriteriaId];
            $sistem  = $systemWeights[$kriteriaId] ?? 0.0;
            $selisih = abs($manual - $sistem);
            $status  = $selisih <= self::DELTA ? '[OK]' : '[!!]';

            echo sprintf(
                "  |  %d   | %-18s | %-5s | %14s | %14s | %4s %s|\n",
                $rank++,
                $label,
                $jenis,
                number_format($manual, 6),
                number_format($sistem, 6),
                number_format($selisih, 4),
                $status
            );
        }

        $totalManual = array_sum($this->expectedWeights);
        $totalSistem = array_sum($systemWeights);
        echo "  +------+--------------------+-------+----------------+----------------+----------+\n";
        echo sprintf(
            "  |      | %-18s |       | %14s | %14s |          |\n",
            'TOTAL',
            number_format($totalManual, 6),
            number_format($totalSistem, 6)
        );
        echo "  +------+--------------------+-------+----------------+----------------+----------+\n";
    }

    private function printUtilityTable(array $systemUtils): void
    {
        $critLabels = ['Kualitas', 'Kelengkpn', 'Variasi ', 'Harga   ', 'Lokasi  ', 'Ulasan  '];
        $headerKol  = implode(' | ', $critLabels);
        $sepKol     = str_repeat('-', 9 * 6 + 5 * 3);

        echo "\n";
        echo "  +---------+{$sepKol}+\n";
        echo "  |  Toko   | {$headerKol} |\n";
        echo "  +---------+{$sepKol}+\n";

        foreach (['Toko A', 'Toko B', 'Toko C'] as $tokoId) {
            $baris = '';
            foreach ($this->orderedKriteria as $kriteriaId) {
                $expectedU = $this->expectedUtilities[$tokoId][$kriteriaId];
                $sistemU   = $systemUtils[$tokoId][$kriteriaId] ?? 0.0;
                $ok        = abs($expectedU - $sistemU) <= self::DELTA;
                $baris    .= sprintf('%s%-8s | ', $ok ? '' : '!! ', number_format($sistemU, 4));
            }
            echo sprintf("  | %-7s | %s\n", $tokoId, $baris);
        }

        echo "  +---------+{$sepKol}+\n";
        echo "\n  Keterangan: Utility skala 0.0000 - 1.0000. Min/Max dari seluruh toko.\n";
    }

    private function printFinalScoresTable(array $rankings, array $manualScores): void
    {
        echo "\n";
        echo "  +----------+------------+------------+------------+----------+---------+\n";
        echo "  |   Toko   | Skor Manual| Skor Sistem|  Selisih   |  Status  | Ranking |\n";
        echo "  +----------+------------+------------+------------+----------+---------+\n";

        $rankingByToko = [];
        foreach ($rankings as $idx => $entry) {
            $rankingByToko[$entry['toko_id']] = ['score' => $entry['score'], 'rank' => $idx + 1];
        }

        foreach (['Toko C', 'Toko A', 'Toko B'] as $tokoId) {
            $manual  = $manualScores[$tokoId] ?? 0.0;
            $sistem  = $rankingByToko[$tokoId]['score'] ?? 0.0;
            $rank    = $rankingByToko[$tokoId]['rank']  ?? '-';
            $selisih = abs($manual - $sistem);
            $status  = $selisih <= self::DELTA ? '[VALID]  ' : '[GAGAL]  ';

            echo sprintf(
                "  | %-8s | %10s | %10s | %10s | %s |   #%-4s|\n",
                $tokoId,
                number_format($manual,  6),
                number_format($sistem,  6),
                number_format($selisih, 6),
                $status,
                $rank
            );
        }

        echo "  +----------+------------+------------+------------+----------+---------+\n";
    }

    private function printFullReport(array $result): void
    {
        $labels = [
            self::K_KUALITAS    => 'Kualitas Produk',
            self::K_KELENGKAPAN => 'Kelengkapan Produk',
            self::K_VARIASI     => 'Variasi Barang',
            self::K_HARGA       => 'Harga',
            self::K_LOKASI      => 'Lokasi',
            self::K_ULASAN      => 'Ulasan',
        ];

        // A. Bobot ROC
        echo "\n  -- A. Bobot ROC --------------------------------------------------\n";
        echo "  +------+--------------------+-------+----------------+\n";
        echo "  | Rank | Kriteria           | Jenis |     Bobot      |\n";
        echo "  +------+--------------------+-------+----------------+\n";
        $rank = 1;
        foreach ($this->orderedKriteria as $kid) {
            echo sprintf(
                "  |  %d   | %-18s | %-5s | %14s |\n",
                $rank++,
                $labels[$kid],
                $this->kriteriaTypes[$kid],
                number_format($result['weights'][$kid], 6)
            );
        }
        echo "  +------+--------------------+-------+----------------+\n";
        echo sprintf("  |      | %-18s |       | %14s |\n", 'TOTAL', number_format(array_sum($result['weights']), 6));
        echo "  +------+--------------------+-------+----------------+\n";

        // B. Matriks Utility
        echo "\n  -- B. Matriks Nilai Utility SMART --------------------------------\n";
        $shortLabels = ['Kualitas', 'Kelengkpn', 'Variasi ', 'Harga   ', 'Lokasi  ', 'Ulasan  '];
        $headerKol   = implode(' | ', $shortLabels);
        $sepKol      = str_repeat('-', 9 * 6 + 5 * 3);
        echo "  +---------+{$sepKol}+\n";
        echo "  |  Toko   | {$headerKol} |\n";
        echo "  +---------+{$sepKol}+\n";
        foreach (['Toko A', 'Toko B', 'Toko C'] as $tokoId) {
            $baris = '';
            foreach ($this->orderedKriteria as $kid) {
                $baris .= sprintf('%-8s | ', number_format($result['utilities'][$tokoId][$kid], 4));
            }
            echo sprintf("  | %-7s | %s\n", $tokoId, $baris);
        }
        echo "  +---------+{$sepKol}+\n";

        // C. Skor Akhir & Ranking
        echo "\n  -- C. Skor Akhir dan Peringkat Toko ------------------------------\n";
        echo "  +---------+---------------------+---------+\n";
        echo "  | Ranking |        Toko         |  Skor   |\n";
        echo "  +---------+---------------------+---------+\n";
        foreach ($result['rankings'] as $idx => $entry) {
            echo sprintf(
                "  |   #%-4d | %-19s | %7s |\n",
                $idx + 1,
                $entry['toko_id'],
                number_format($entry['score'], 6)
            );
        }
        echo "  +---------+---------------------+---------+\n";
    }
}
