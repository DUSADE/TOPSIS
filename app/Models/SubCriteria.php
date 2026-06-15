<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubCriteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'criteria_id',
        'label',
        'value',
        'sequence',
    ];

    protected $casts = [
        'value' => 'float',
        'sequence' => 'integer',
    ];

    public function criteria(): BelongsTo
    {
        return $this->belongsTo(Criteria::class);
    }
}
