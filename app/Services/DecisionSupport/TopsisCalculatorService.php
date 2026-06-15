<?php

declare(strict_types=1);

namespace App\Services\DecisionSupport;

use App\Models\Criteria;
use Illuminate\Support\Collection;

class TopsisCalculatorService
{
    public function __construct(
        protected NormalizationService $normalizer
    ) {}

    /**
     * Menghitung skor TOPSIS.
     *
     * Karakteristik:
     * - Menggunakan metode TOPSIS asli.
     * - Mendukung bobot dalam persen (16, 15, 13, dst.) maupun desimal (0.16, 0.15).
     * - Jika semua nilai = 5, maka skor = 1.0000 (100%).
     * - Jika semua alternatif identik, skor mengikuti weighted average.
     * - Skor akhir = 70% TOPSIS + 30% weighted average.
     *
     * Return format:
     * [
     *     prospect_id => 0.9234
     * ]
     *
     * @param Collection $prospects
     * @return array<int, float>
     */
    public function calculate(Collection $prospects): array
    {
        if ($prospects->isEmpty()) {
            return [];
        }

        // ==========================================================
        // 1. Ambil kriteria aktif
        // ==========================================================
        $criterias = Criteria::active()->get();

        if ($criterias->isEmpty()) {
            return [];
        }

        // ==========================================================
        // 2. Konfigurasi bobot dan tipe
        // ==========================================================
        $configById = $criterias->mapWithKeys(function ($criteria) {
            $weight = (float) $criteria->weight;

            // Jika bobot disimpan sebagai persen (mis. 16)
            if ($weight > 1) {
                $weight = $weight / 100;
            }

            return [
                $criteria->id => [
                    'weight' => $weight,
                    'type'   => strtoupper((string) $criteria->type), // BENEFIT / COST
                ],
            ];
        });

        $criteriaIds = $configById->keys()->toArray();

        if (empty($criteriaIds)) {
            return [];
        }

        // ==========================================================
        // 3. Filter prospek dengan evaluasi lengkap
        // ==========================================================
        $eligibleProspects = $prospects->filter(
            fn ($prospect) => $prospect->hasCompleteEvaluation($criteriaIds)
        );

        if ($eligibleProspects->isEmpty()) {
            return [];
        }

        // ==========================================================
        // 4. Jika hanya satu prospek
        // ==========================================================
        if ($eligibleProspects->count() === 1) {
            $prospect = $eligibleProspects->first();
            $evaluations = $prospect->getEvaluationMatrix();

            $weightedSum = 0.0;
            $totalWeight = 0.0;
            $allPerfect = true;

            foreach ($criteriaIds as $criteriaId) {
                $value = (float) ($evaluations[$criteriaId] ?? 0); // 1..5
                $weight = $configById[$criteriaId]['weight'];

                // Cek apakah semua nilai = 5
                if ($value < 5.0) {
                    $allPerfect = false;
                }

                // Normalisasi 1..5 ke 0..1
                $normalizedValue = $value / 5.0;

                $weightedSum += $normalizedValue * $weight;
                $totalWeight += $weight;
            }

            // Jika semua nilai = 5, langsung skor sempurna
            if ($allPerfect) {
                $score = 1.0000;
            } else {
                $score = $totalWeight > 0
                    ? $weightedSum / $totalWeight
                    : 0.0;
            }

            return [
                $prospect->id => round($score, 4),
            ];
        }

        // ==========================================================
        // 5. Matriks keputusan
        // ==========================================================
        $matrix = [];

        foreach ($eligibleProspects as $prospect) {
            $evaluations = $prospect->getEvaluationMatrix();
            $row = [];
            $allPerfect = true;

            foreach ($criteriaIds as $criteriaId) {
                $value = (float) ($evaluations[$criteriaId] ?? 0);
                $row[$criteriaId] = $value;

                // Cek apakah semua nilai = 5
                if ($value < 5.0) {
                    $allPerfect = false;
                }
            }

            // Simpan penanda apakah prospek ini sempurna
            $row['_all_perfect'] = $allPerfect;

            $matrix[$prospect->id] = $row;
        }

        // Hapus kolom helper sebelum normalisasi
        $matrixForNormalization = [];
        foreach ($matrix as $prospectId => $row) {
            $matrixForNormalization[$prospectId] = array_intersect_key(
                $row,
                array_flip($criteriaIds)
            );
        }

        // ==========================================================
        // 6. Normalisasi
        // ==========================================================
        $normalized = $this->normalizer->normalize(
            $matrixForNormalization,
            $criteriaIds
        );

        // ==========================================================
        // 7. Pembobotan
        // ==========================================================
        $weighted = [];

        foreach ($normalized as $prospectId => $row) {
            foreach ($row as $criteriaId => $value) {
                $weighted[$prospectId][$criteriaId] =
                    $value * $configById[$criteriaId]['weight'];
            }
        }

        // ==========================================================
        // 8. Solusi ideal positif dan negatif
        // ==========================================================
        $idealPositive = [];
        $idealNegative = [];

        foreach ($criteriaIds as $criteriaId) {
            $columnValues = array_column($weighted, $criteriaId);

            if (empty($columnValues)) {
                $idealPositive[$criteriaId] = 0.0;
                $idealNegative[$criteriaId] = 0.0;
                continue;
            }

            $type = $configById[$criteriaId]['type'];

            if ($type === 'BENEFIT') {
                $idealPositive[$criteriaId] = max($columnValues);
                $idealNegative[$criteriaId] = min($columnValues);
            } else {
                $idealPositive[$criteriaId] = min($columnValues);
                $idealNegative[$criteriaId] = max($columnValues);
            }
        }

        // ==========================================================
        // 9. Total bobot
        // ==========================================================
        $totalWeight = array_sum(
            array_map(
                fn ($config) => $config['weight'],
                $configById->toArray()
            )
        );

        // ==========================================================
        // 10. Hitung skor akhir
        // ==========================================================
        $scores = [];

        foreach ($weighted as $prospectId => $row) {
            // ======================================================
            // PRIORITAS UTAMA:
            // Jika semua nilai kriteria = 5,
            // maka skor wajib 1.0000 (100%)
            // ======================================================
            if ($matrix[$prospectId]['_all_perfect'] === true) {
                $scores[$prospectId] = 1.0000;
                continue;
            }

            // --------------------------
            // A. Hitung TOPSIS asli
            // --------------------------
            $sumPositive = 0.0;
            $sumNegative = 0.0;

            foreach ($criteriaIds as $criteriaId) {
                $value = $row[$criteriaId];

                $sumPositive += pow(
                    $value - $idealPositive[$criteriaId],
                    2
                );

                $sumNegative += pow(
                    $value - $idealNegative[$criteriaId],
                    2
                );
            }

            $dPositive = sqrt($sumPositive);
            $dNegative = sqrt($sumNegative);

            // --------------------------
            // B. Weighted Average
            // --------------------------
            $weightedAverage = array_sum($row);

            if ($totalWeight > 0) {
                $weightedAverage = $weightedAverage / $totalWeight;
            }

            $weightedAverage = max(0.0, min(1.0, $weightedAverage));

            // --------------------------
            // C. Skor akhir
            // --------------------------
            if (($dNegative + $dPositive) == 0.0) {
                // Semua alternatif identik
                $finalScore = $weightedAverage;
            } else {
                $topsisScore = $dNegative / ($dNegative + $dPositive);

                // Kombinasi 70% TOPSIS + 30% Weighted Average
                $finalScore =
                    ($topsisScore * 0.7) +
                    ($weightedAverage * 0.3);
            }

            // Pastikan tetap dalam rentang 0..1
            $finalScore = max(0.0, min(1.0, $finalScore));

            $scores[$prospectId] = round($finalScore, 4);
        }

        // ==========================================================
        // 11. Urutkan skor tertinggi ke terendah
        // ==========================================================
        arsort($scores);

        return $scores;
    }

    /**
     * Mengubah skor 0..1 menjadi persentase 0..100.
     *
     * Contoh:
     * 0.8400 => 84.00
     * 1.0000 => 100.00
     */
    public function getAccuracyPercentage(float $score): float
    {
        return round($score * 100, 2);
    }

    /**
     * Format persentase siap tampil.
     *
     * Contoh:
     * 0.8400 => "84.00%"
     */
    public function getFormattedAccuracy(float $score): string
    {
        return number_format($this->getAccuracyPercentage($score), 2) . '%';
    }

    /**
     * Interpretasi skor.
     *
     * 0.90 – 1.00 : Sangat layak / prioritas utama
     * 0.75 – 0.89 : Layak dan sangat potensial
     * 0.50 – 0.74 : Cukup potensial
     * 0.25 – 0.49 : Potensi rendah
     * 0.00 – 0.24 : Tidak diprioritaskan
     */
    public function getInterpretation(float $score): string
    {
        if ($score >= 0.90) {
            return 'Sangat layak / prioritas utama';
        }

        if ($score >= 0.75) {
            return 'Layak dan sangat potensial';
        }

        if ($score >= 0.50) {
            return 'Cukup potensial';
        }

        if ($score >= 0.25) {
            return 'Potensi rendah';
        }

        return 'Tidak diprioritaskan';
    }
}