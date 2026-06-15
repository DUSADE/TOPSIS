<?php

namespace App\Http\Controllers;

use App\Models\Prospect;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $prospectQuery = Prospect::query();
        if ($user->role === 'sales') {
            $prospectQuery->where('user_id', $user->id);
        }

        $totalProspects = (clone $prospectQuery)->count();
        $rankedProspects = (clone $prospectQuery)->whereNotNull('spk_score')->count();
        $pendingEvaluationProspects = max($totalProspects - $rankedProspects, 0);
        $topProspects = (clone $prospectQuery)
            ->whereNotNull('spk_score')
            ->orderBy('spk_score', 'desc')
            ->take(5)
            ->get();
        
        $salesPerformance = null;
        if ($user->role === 'admin' || $user->role === 'pimpinan') {
            $salesPerformance = User::where('role', 'sales')
                ->withCount('prospects')
                ->orderBy('prospects_count', 'desc')
                ->get();
        }

        return view('dashboard', compact(
            'totalProspects',
            'rankedProspects',
            'pendingEvaluationProspects',
            'topProspects',
            'salesPerformance'
        ));
    }
}
