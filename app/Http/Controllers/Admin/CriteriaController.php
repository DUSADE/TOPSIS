<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCriteriaRequest;
use App\Models\Criteria;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    public function index()
    {
        $criterias = Criteria::withCount('subCriterias')->orderBy('code')->get();
        return view('admin.criterias.index', compact('criterias'));
    }

    public function create()
    {
        return view('admin.criterias.create');
    }

    public function store(StoreCriteriaRequest $request)
    {
        Criteria::create($request->validated());
        return redirect()->route('criterias.index')->with('success', 'Kriteria berhasil ditambahkan.');
    }

    public function edit(Criteria $criteria)
    {
        $criteria->load(['subCriterias' => function($q) {
            $q->orderBy('sequence', 'asc');
        }]);
        return view('admin.criterias.edit', compact('criteria'));
    }

    public function update(Request $request, Criteria $criteria)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'type' => 'in:BENEFIT,COST',
            'weight' => 'numeric|min:0',
            'is_active' => 'boolean',
        ]);

        // Handle checkbox boolean
        $validated['is_active'] = $request->has('is_active');

        $criteria->update($validated);

        return redirect()->route('criterias.index')->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function destroy(Criteria $criteria)
    {
        $criteria->delete();
        return redirect()->route('criterias.index')->with('success', 'Kriteria berhasil dihapus.');
    }
}
