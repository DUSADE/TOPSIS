<?php

declare(strict_types=1);

namespace App\Services\DecisionSupport;

class NormalizationService
{
    /**
     * Normalize the decision matrix using Vector Normalization.
     * Formula: r_ij = x_ij / sqrt(sum(x_ij^2) for all rows)
     *
     * @param array $matrix [prospect_id => [criteria_code => value]]
     * @param array $criteriaCodes List of criteria codes to normalize
     * @return array Normalized matrix [prospect_id => [criteria_code => normalized_value]]
     */
    public function normalize(array $matrix, array $criteriaCodes): array
    {
        $normalizedMatrix = [];
        $divisors = [];

        // 1. Calculate divisors (sqrt of sum of squares) for each criteria
        foreach ($criteriaCodes as $code) {
            $sumSquares = 0;
            foreach ($matrix as $prospectId => $row) {
                $val = $row[$code] ?? 0;
                $sumSquares += pow((float)$val, 2);
            }
            $divisors[$code] = sqrt($sumSquares);
        }

        // 2. Divide each value by the divisor
        foreach ($matrix as $prospectId => $row) {
            foreach ($criteriaCodes as $code) {
                $val = $row[$code] ?? 0;
                $divisor = $divisors[$code];
                
                // Avoid division by zero
                $normalizedMatrix[$prospectId][$code] = $divisor > 0 
                    ? $val / $divisor 
                    : 0;
            }
        }

        return $normalizedMatrix;
    }
}
