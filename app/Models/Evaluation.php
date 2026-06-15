<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'prospect_id',
        'criteria_id',
        'sub_criteria_id',
        'raw_value',
    ];

    protected $casts = [
        'raw_value' => 'float',
    ];

    public function prospect(): BelongsTo
    {
        return $this->belongsTo(Prospect::class);
    }

    public function criteria(): BelongsTo
    {
        return $this->belongsTo(Criteria::class);
    }

    public function subCriteria(): BelongsTo
    {
        return $this->belongsTo(SubCriteria::class);
    }
}
