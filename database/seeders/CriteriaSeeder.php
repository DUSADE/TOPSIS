<?php

namespace Database\Seeders;

use App\Models\Criteria;
use App\Support\CriteriaCatalog;
use Illuminate\Database\Seeder;

class CriteriaSeeder extends Seeder
{
    public function run(): void
    {
        foreach (CriteriaCatalog::definitions() as $definition) {
            $criteria = Criteria::updateOrCreate(
                ['code' => $definition['code']],
                [
                    'name' => $definition['name'],
                    'type' => $definition['type'],
                    'weight' => $definition['weight'],
                    'is_active' => true,
                ]
            );

            $existingSubCriterias = $criteria->subCriterias()
                ->orderBy('sequence')
                ->get(['label', 'value', 'sequence'])
                ->map(fn ($sub) => [
                    'label' => $sub->label,
                    'value' => (float) $sub->value,
                    'sequence' => $sub->sequence,
                ])
                ->all();

            $expectedSubCriterias = collect($definition['sub_criterias'])
                ->map(fn ($sub) => [
                    'label' => $sub['label'],
                    'value' => (float) $sub['value'],
                    'sequence' => $sub['sequence'],
                ])
                ->all();

            if ($existingSubCriterias !== $expectedSubCriterias) {
                $criteria->subCriterias()->delete();
                $criteria->subCriterias()->createMany($definition['sub_criterias']);
            }
        }
    }
}
