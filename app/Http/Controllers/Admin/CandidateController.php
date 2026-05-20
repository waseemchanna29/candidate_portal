<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function index(Request $request)
    {
        $query = Candidate::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('cnic', 'like', "%{$search}%")
                  ->orWhere('unique_code', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('email', 'like', "%{$search}%"));
            });
        }

        $candidates = $query->latest()->paginate(15);
        return view('admin.candidates.index', compact('candidates'));
    }

    public function show(Candidate $candidate)
    {
        $candidate->load(['user', 'educations', 'experiences', 'paymentReceipt', 'course.pricingModel']);
        return view('admin.candidates.show', compact('candidate'));
    }
    

    public function approve(Request $request, Candidate $candidate)
    {
        $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $uniqueCode = 'CP-' . strtoupper(substr(md5($candidate->id . time()), 0, 8));

        $candidate->update([
            'status'      => 'approved',
            'unique_code' => $uniqueCode,
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', "Candidate approved successfully. Unique Code: {$uniqueCode}");
    }

    public function reject(Request $request, Candidate $candidate)
    {
        $request->validate([
            'admin_notes' => ['required', 'string', 'max:1000'],
        ]);

        $candidate->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Candidate application has been rejected.');
    }

    
}