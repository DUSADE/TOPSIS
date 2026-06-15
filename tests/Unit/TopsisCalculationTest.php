<?php

namespace Tests\Unit;

use App\Models\Criteria;
use App\Models\Prospect;
use App\Services\DecisionSupport\NormalizationService;
use App\Services\DecisionSupport\TopsisCalculatorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

class TopsisCalculationTest extends TestCase
{
    use RefreshDatabase;

    public function test_simple_topsis_calculation()
    {
        Criteria::query()->delete();

        // 1. Setup Criteria
        $c1 = Criteria::create(['code' => 'C1', 'name' => 'C1', 'type' => 'BENEFIT', 'weight' => 0.5, 'is_active' => true]);
        $c2 = Criteria::create(['code' => 'C2', 'name' => 'C2', 'type' => 'COST',    'weight' => 0.5, 'is_active' => true]);
        $c1High = $c1->subCriterias()->create(['label' => 'High', 'value' => 4, 'sequence' => 1]);
        $c1Low = $c1->subCriterias()->create(['label' => 'Low', 'value' => 2, 'sequence' => 2]);
        $c2Cheap = $c2->subCriterias()->create(['label' => 'Cheap', 'value' => 10, 'sequence' => 1]);
        $c2Expensive = $c2->subCriterias()->create(['label' => 'Expensive', 'value' => 100, 'sequence' => 2]);

        // 2. Setup Prospects
        $user = \App\Models\User::factory()->create();
        
        $p1 = Prospect::create(['user_id' => $user->id, 'name' => 'P1', 'phone' => '1', 'status' => 'NEW']);
        $p2 = Prospect::create(['user_id' => $user->id, 'name' => 'P2', 'phone' => '2', 'status' => 'NEW']);

        // 3. Setup Evaluations (Directly)
        // P1: C1=4 (Good), C2=10 (Cheap)
        // P2: C1=2 (Bad),  C2=100 (Expensive)
        // P1 should win.
        
        $p1->evaluations()->create(['criteria_id' => $c1->id, 'sub_criteria_id' => $c1High->id, 'raw_value' => 4]);
        $p1->evaluations()->create(['criteria_id' => $c2->id, 'sub_criteria_id' => $c2Cheap->id, 'raw_value' => 10]);

        $p2->evaluations()->create(['criteria_id' => $c1->id, 'sub_criteria_id' => $c1Low->id, 'raw_value' => 2]);
        $p2->evaluations()->create(['criteria_id' => $c2->id, 'sub_criteria_id' => $c2Expensive->id, 'raw_value' => 100]);

        // 4. Run Service
        $service = new TopsisCalculatorService(new NormalizationService());
        $results = $service->calculate(Collection::make([$p1, $p2]));

        // 5. Assert
        // P1 should be close to 1
        // P2 should be close to 0
        $this->assertGreaterThan($results[$p2->id], $results[$p1->id], "P1 should rank higher than P2");
        
        echo "\nScores: P1={$results[$p1->id]}, P2={$results[$p2->id]}\n";
    }

    public function test_incomplete_prospect_is_excluded_from_ranking()
    {
        Criteria::query()->delete();

        $c1 = Criteria::create(['code' => 'C1', 'name' => 'C1', 'type' => 'BENEFIT', 'weight' => 0.5, 'is_active' => true]);
        $c2 = Criteria::create(['code' => 'C2', 'name' => 'C2', 'type' => 'BENEFIT', 'weight' => 0.5, 'is_active' => true]);
        $s11 = $c1->subCriterias()->create(['label' => 'A', 'value' => 4, 'sequence' => 1]);
        $s12 = $c1->subCriterias()->create(['label' => 'B', 'value' => 3, 'sequence' => 2]);
        $s21 = $c2->subCriterias()->create(['label' => 'C', 'value' => 5, 'sequence' => 1]);

        $user = \App\Models\User::factory()->create();

        $complete = Prospect::create(['user_id' => $user->id, 'name' => 'Lengkap', 'phone' => '1', 'status' => 'NEW']);
        $incomplete = Prospect::create(['user_id' => $user->id, 'name' => 'Belum Lengkap', 'phone' => '2', 'status' => 'NEW']);

        $complete->evaluations()->create(['criteria_id' => $c1->id, 'sub_criteria_id' => $s11->id, 'raw_value' => 4]);
        $complete->evaluations()->create(['criteria_id' => $c2->id, 'sub_criteria_id' => $s21->id, 'raw_value' => 5]);
        $incomplete->evaluations()->create(['criteria_id' => $c1->id, 'sub_criteria_id' => $s12->id, 'raw_value' => 3]);

        $service = new TopsisCalculatorService(new NormalizationService());
        $results = $service->calculate(Collection::make([$complete, $incomplete]));

        $this->assertArrayHasKey($complete->id, $results);
        $this->assertArrayNotHasKey($incomplete->id, $results);
    }
}
