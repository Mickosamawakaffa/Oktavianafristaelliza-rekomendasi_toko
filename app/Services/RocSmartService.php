<?php

namespace App\Services;

class RocSmartService
{
    /**
     * Menghitung jarak lurus kelengkungan bumi antara dua koordinat GPS (Haversine Formula).
     *
     * @param float $lat1 Latitude titik 1 (misal User)
     * @param float $lon1 Longitude titik 1
     * @param float $lat2 Latitude titik 2 (misal Toko)
     * @param float $lon2 Longitude titik 2
     * @return float Jarak dalam kilometer (KM)
     */
    public function calculateHaversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // Jari-jari bumi dalam KM

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    /**
     * 1. Menghitung bobot ROC (Rank Order Centroid) berdasarkan urutan prioritas kriteria.
     *
     * @param array $orderedKriteriaIds Array ID kriteria berurutan dari prioritas tertinggi (rank 1) ke terendah (rank m),
     *                                   atau array asosiatif [kriteria_id => urutan_rank].
     * @return array<int|string, float> Array asosiatif [kriteria_id => bobot_roc]
     */
    public function calculateRocWeights(array $orderedKriteriaIds): array
    {
        // Normalisasi format input menjadi array [kriteria_id => rank_integer (1..m)]
        $kriteriaRanks = [];

        // Cek jika input berbentuk list kriteria_id terurut [id1, id2, id3]
        if (array_is_list($orderedKriteriaIds)) {
            foreach ($orderedKriteriaIds as $index => $kriteriaId) {
                $kriteriaRanks[$kriteriaId] = $index + 1; // 1-indexed
            }
        } else {
            // Input sudah berupa [kriteria_id => rank]
            $kriteriaRanks = $orderedKriteriaIds;
        }

        $m = count($kriteriaRanks);
        if ($m === 0) {
            return [];
        }

        $weights = [];

        foreach ($kriteriaRanks as $kriteriaId => $k) {
            // Formula ROC: W_k = (1/m) * sum(1/i) untuk i = k sampai m
            $sum = 0.0;
            for ($i = $k; $i <= $m; $i++) {
                $sum += (1.0 / $i);
            }
            $weights[$kriteriaId] = (1.0 / $m) * $sum;
        }

        return $weights;
    }

    /**
     * 2. Menghitung nilai utility SMART untuk satu nilai tunggal.
     *
     * @param float $val Nilai kriteria toko saat ini
     * @param float $min Nilai minimum kriteria dari seluruh toko
     * @param float $max Nilai maksimum kriteria dari seluruh toko
     * @param string $jenis Jenis kriteria: 'benefit' atau 'cost'
     * @return float Nilai utility antara 0.0 sampai 1.0
     */
    public function calculateUtility(float $val, float $min, float $max, string $jenis): float
    {
        // Jika min dan max sama (seluruh toko bernilai sama pada kriteria ini)
        if (abs($max - $min) < 1e-9) {
            return 1.0;
        }

        $jenisLower = strtolower($jenis);

        if ($jenisLower === 'cost') {
            // Rumus Cost: (C_max - C_i) / (C_max - C_min)
            return ($max - $val) / ($max - $min);
        }

        // Rumus Benefit: (C_i - C_min) / (C_max - C_min)
        return ($val - $min) / ($max - $min);
    }

    /**
     * Menghitung matriks utility SMART untuk seluruh toko & kriteria.
     *
     * @param array<int|string, array<int|string, float>> $alternativesValues Matrix [toko_id => [kriteria_id => nilai]]
     * @param array<int|string, string> $kriteriaTypes Array [kriteria_id => 'benefit'|'cost']
     * @return array<int|string, array<int|string, float>> Matrix utility [toko_id => [kriteria_id => utility]]
     */
    public function calculateUtilitiesForMatrix(array $alternativesValues, array $kriteriaTypes): array
    {
        if (empty($alternativesValues)) {
            return [];
        }

        // Cari C_min dan C_max per kriteria
        $minValues = [];
        $maxValues = [];

        foreach ($alternativesValues as $tokoId => $kriteriaVals) {
            foreach ($kriteriaVals as $kriteriaId => $val) {
                if (!isset($minValues[$kriteriaId])) {
                    $minValues[$kriteriaId] = $val;
                    $maxValues[$kriteriaId] = $val;
                } else {
                    $minValues[$kriteriaId] = min($minValues[$kriteriaId], $val);
                    $maxValues[$kriteriaId] = max($maxValues[$kriteriaId], $val);
                }
            }
        }

        // Hitung utility per toko dan kriteria
        $utilities = [];
        foreach ($alternativesValues as $tokoId => $kriteriaVals) {
            $utilities[$tokoId] = [];
            foreach ($kriteriaVals as $kriteriaId => $val) {
                $jenis = $kriteriaTypes[$kriteriaId] ?? 'benefit';
                $min = $minValues[$kriteriaId] ?? 0.0;
                $max = $maxValues[$kriteriaId] ?? 0.0;

                $utilities[$tokoId][$kriteriaId] = $this->calculateUtility($val, $min, $max, $jenis);
            }
        }

        return $utilities;
    }

    /**
     * 3. Menghitung nilai akhir (Vi) tiap toko dan mengurutkan dari tertinggi ke terendah.
     *
     * @param array<int|string, array<int|string, float>> $utilitiesMatrix Matrix utility [toko_id => [kriteria_id => utility]]
     * @param array<int|string, float> $weights Bobot ROC per kriteria [kriteria_id => bobot]
     * @return array List toko beserta nilai akhir (score), terurut dari nilai terbesar ke terkecil
     */
    public function calculateFinalScores(array $utilitiesMatrix, array $weights): array
    {
        $results = [];

        foreach ($utilitiesMatrix as $tokoId => $kriteriaUtils) {
            $totalScore = 0.0;
            foreach ($kriteriaUtils as $kriteriaId => $uVal) {
                $wVal = $weights[$kriteriaId] ?? 0.0;
                $totalScore += ($uVal * $wVal);
            }

            $results[] = [
                'toko_id' => $tokoId,
                'score' => $totalScore,
                'utilities' => $kriteriaUtils,
            ];
        }

        // Urutkan dari nilai akhir (score) terbesar ke terkecil
        usort($results, function ($a, $b) {
            if (abs($b['score'] - $a['score']) < 1e-9) {
                return 0;
            }
            return ($b['score'] > $a['score']) ? 1 : -1;
        });

        return $results;
    }

    /**
     * Workflow LENGKAP ROC + SMART:
     * Menghitung rangkuman dari urutan kriteria & nilai alternatif toko sampai ranking akhir toko.
     *
     * @param array $orderedKriteriaIds Array ID kriteria dalam urutan prioritas
     * @param array $alternativesValues Matrix nilai [toko_id => [kriteria_id => nilai]]
     * @param array $kriteriaTypes Array jenis kriteria [kriteria_id => 'benefit'|'cost']
     * @return array [ 'weights' => [...], 'utilities' => [...], 'rankings' => [...] ]
     */
    public function processRocSmart(array $orderedKriteriaIds, array $alternativesValues, array $kriteriaTypes): array
    {
        $weights = $this->calculateRocWeights($orderedKriteriaIds);
        $utilities = $this->calculateUtilitiesForMatrix($alternativesValues, $kriteriaTypes);
        $rankings = $this->calculateFinalScores($utilities, $weights);

        return [
            'weights' => $weights,
            'utilities' => $utilities,
            'rankings' => $rankings,
        ];
    }
}
