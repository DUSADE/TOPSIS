<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\SubCriteria;
use Illuminate\Http\Request;

class SubCriteriaController extends Controller
{
    public function store(Request $request, Criteria $criteria)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'value' => 'required|numeric',
            'sequence' => 'integer',
        ]);

        $criteria->subCriterias()->create($validated);
        return back()->with('success', 'Sub-Kriteria berhasil ditambahkan.');
    }

    public function update(Request $request, SubCriteria $subCriteria)
    {
        $validated = $request->validate([
            'label' => 'string|max:255',
            'value' => 'numeric',
            'sequence' => 'integer',
        ]);

        $subCriteria->update($validated);
        return back()->with('success', 'Sub-Kriteria berhasil diperbarui.');
    }

    public function destroy(Criteria $criteria, SubCriteria $subCriteria)
    {
        $subCriteria->delete();
        return back()->with('success', 'Sub-Kriteria dihapus.');
    }
}
