<?php

declare(strict_types=1);

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Prospect;
use App\Models\SubCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    public function store(Request $request, Prospect $prospect)
    {
        // Isolation for Sales
        if (auth()->user()->role === 'sales' && $prospect->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak. Anda hanya dapat mengevaluasi prospek milik sendiri.');
        }

        // Expect input: evaluations => [criteria_id => sub_criteria_id]
        $validated = $request->validate([
            'evaluations' => 'required|array',
            'evaluations.*' => 'exists:sub_criterias,id',
        ]);

        DB::transaction(function () use ($prospect, $validated) {
            foreach ($validated['evaluations'] as $criteriaId => $subCriteriaId) {
                $sub = SubCriteria::query()
                    ->where('id', $subCriteriaId)
                    ->where('criteria_id', $criteriaId)
                    ->firstOrFail();
                
                Evaluation::updateOrCreate(
                    [
                        'prospect_id' => $prospect->id,
                        'criteria_id' => $criteriaId,
                    ],
                    [
                        'sub_criteria_id' => $subCriteriaId,
                        'raw_value' => $sub->value, // Snapshot value
                    ]
                );
            }

            if (!in_array($prospect->status, ['WON', 'LOST'])) {
                $prospect->status = 'QUALIFIED';
                $prospect->save();
            }

            $allProspects = Prospect::with('evaluations')->get();
            $calculator = app(\App\Services\DecisionSupport\TopsisCalculatorService::class);
            $scores = $calculator->calculate($allProspects);

            Prospect::query()->update(['spk_score' => null]);

            foreach ($scores as $id => $score) {
                Prospect::where('id', $id)->update(['spk_score' => $score]);
            }
        });

        return back()->with('success', 'Evaluasi disimpan. Skor TOPSIS diperbarui untuk prospek dengan penilaian lengkap.');
    }
}
