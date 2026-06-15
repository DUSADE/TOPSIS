<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Criteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'weight',
        'is_active',
    ];

    protected $casts = [
        'weight' => 'float',
        'is_active' => 'boolean',
    ];

    public function subCriterias(): HasMany
    {
        return $this->hasMany(SubCriteria::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
