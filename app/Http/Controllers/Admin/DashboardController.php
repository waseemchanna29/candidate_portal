<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Course;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total'    => Candidate::count(),
            'pending'  => Candidate::where('status', 'pending')->count(),
            'approved' => Candidate::where('status', 'approved')->count(),
            'rejected' => Candidate::where('status', 'rejected')->count(),
             'courses'  => Course::where('is_active', true)->count(),
        ];
        $recentCandidates = Candidate::with('user')->latest()->take(5)->get();
        return view('admin.dashboard', compact('stats', 'recentCandidates'));
    }
}