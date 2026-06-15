<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prospect extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'status',
        'spk_score',
        'metadata',
    ];

    protected $casts = [
        'spk_score' => 'float',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    /**
     * Helper to get evaluations as a simple [criteria_id => raw_value] array.
     */
    public function getEvaluationMatrix(): array
    {
        return $this->evaluations->pluck('raw_value', 'criteria_id')->toArray();
    }

    public function hasCompleteEvaluation(array $activeCriteriaIds): bool
    {
        if ($activeCriteriaIds === []) {
            return false;
        }

        $completedCriteriaIds = $this->evaluations
            ->filter(fn (Evaluation $evaluation) => $evaluation->sub_criteria_id !== null)
            ->pluck('criteria_id')
            ->intersect($activeCriteriaIds)
            ->unique()
            ->values()
            ->all();

        sort($completedCriteriaIds);
        $expected = $activeCriteriaIds;
        sort($expected);

        return $completedCriteriaIds === $expected;
    }
}
