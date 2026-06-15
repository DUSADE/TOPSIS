<?php

declare(strict_types=1);

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use App\Models\Prospect;
use Illuminate\Http\Request;

class ProspectController extends Controller
{
    public function index(Request $request)
    {
        $query = Prospect::with('user');

        // Isolation for Sales: Only see own prospects
        if (auth()->user()->role === 'sales') {
            $query->where('user_id', auth()->id());
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Sort handling
        $sort = $request->get('sort', 'terbaru');
        
        match ($sort) {
            'terlama' => $query->oldest(),
            'score_tertinggi' => $query->orderByRaw('spk_score IS NULL, spk_score DESC'),
            'score_terendah' => $query->orderByRaw('spk_score IS NULL, spk_score ASC'),
            default => $query->latest(), // 'terbaru'
        };

        // Handle per_page
        $perPage = $request->get('per_page', 10);
        $prospects = $query->paginate($perPage)->withQueryString();

        return view('prospects.index', compact('prospects'));
    }

    public function create()
    {
        return view('prospects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'status' => 'required|in:NEW,CONTACTED,QUALIFIED,LOST,WON',
            'preferred_orientation' => 'nullable|in:TIMUR,BARAT,SELATAN,UTARA',
        ]);

        $validated['user_id'] = auth()->id() ?? 1;
        $validated['metadata'] = $this->buildMetadata(
            null,
            $request->input('preferred_orientation')
        );
        unset($validated['preferred_orientation']);

        $prospect = Prospect::create($validated);

        return redirect()->route('prospects.show', $prospect)->with('success', 'Data prospek berhasil ditambahkan.');
    }

    public function show(Prospect $prospect)
    {
        // Isolation for Sales
        if (auth()->user()->role === 'sales' && $prospect->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak. Anda hanya dapat melihat prospek milik sendiri.');
        }

        // Load relationships needed for evaluation
        $prospect->load(['evaluations', 'user']);
        
        // Get all active criteria with their sub-criteria for the form
        $criterias = Criteria::active()->with(['subCriterias' => function($q) {
            $q->orderBy('sequence', 'asc');
        }])->get();

        return view('prospects.show', compact('prospect', 'criterias'));
    }

    public function update(Request $request, Prospect $prospect)
    {
        // Isolation for Sales
        if (auth()->user()->role === 'sales' && $prospect->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak. Anda hanya dapat mengedit prospek milik sendiri.');
        }

        // Basic update (status etc)
        $validated = $request->validate([
            'name' => 'string|max:255',
            'phone' => 'string|max:20',
            'email' => 'nullable|email',
            'status' => 'in:NEW,CONTACTED,QUALIFIED,LOST,WON',
            'notes' => 'nullable|string|max:1000',
            'preferred_orientation' => 'nullable|in:TIMUR,BARAT,SELATAN,UTARA',
        ]);

        if ($request->has('notes') || $request->has('preferred_orientation')) {
            $validated['metadata'] = $this->buildMetadata(
                $prospect->metadata,
                $request->input('preferred_orientation'),
                $request->input('notes')
            );
        }

        unset($validated['preferred_orientation'], $validated['notes']);
        $prospect->update($validated);
        return back()->with('success', 'Data prospek diperbarui.');
    }

    private function buildMetadata(?array $existing = null, ?string $preferredOrientation = null, ?string $notes = null): array
    {
        $metadata = $existing ?? [];

        if ($preferredOrientation) {
            $metadata['preferred_orientation'] = $preferredOrientation;
        } else {
            unset($metadata['preferred_orientation']);
        }

        if ($notes !== null) {
            if ($notes === '') {
                unset($metadata['notes']);
            } else {
                $metadata['notes'] = $notes;
            }
        }

        return $metadata;
    }
}
