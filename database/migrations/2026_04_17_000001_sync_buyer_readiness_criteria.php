<?php

use App\Models\Criteria;
use App\Models\Prospect;
use App\Support\CriteriaCatalog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function (): void {
            $activeCodes = CriteriaCatalog::codes();

            foreach (CriteriaCatalog::definitions() as $definition) {
                $criteria = Criteria::query()->updateOrCreate(
                    ['code' => $definition['code']],
                    [
                        'name' => $definition['name'],
                        'type' => $definition['type'],
                        'weight' => $definition['weight'],
                        'is_active' => true,
                    ]
                );

                $criteria->subCriterias()->delete();
                $criteria->subCriterias()->createMany($definition['sub_criterias']);
            }

            Criteria::query()
                ->whereNotIn('code', $activeCodes)
                ->update(['is_active' => false]);

            Prospect::query()->update(['spk_score' => null]);
        });
    }

    public function down(): void
    {
        //
    }
};
