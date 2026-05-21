<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
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
        $candidate->load(['user', 'educations', 'experiences', 'paymentReceipt', 'course.pricingModel', 'batch']);

        return view('admin.candidates.show', compact('candidate'));
    }


    public function approve(Request $request, Candidate $candidate)
    {
        $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:1000'],
            'batch_id'    => ['nullable', 'exists:batches,id'],
        ]);

        $isWaitlisted = false;

        if ($request->batch_id) {
            $batch = \App\Models\Batch::find($request->batch_id);

            if ($batch->seats_available <= 0) {
                // Batch is full — waitlist the candidate
                $isWaitlisted = true;
            } else {
                // Fill seat — auto-mark full if last seat taken
                if ($batch->seats_available - 1 <= 0) {
                    $batch->update(['status' => 'full']);
                }
            }
        }

        $uniqueCode = 'CP-' . strtoupper(substr(md5($candidate->id . time()), 0, 8));

        $candidate->update([
            'status'        => 'approved',
            'unique_code'   => $uniqueCode,
            'batch_id'      => $request->batch_id ?: null,
            'is_waitlisted' => $isWaitlisted,
            'admin_notes'   => $isWaitlisted
                ? trim(($request->admin_notes ? $request->admin_notes . ' | ' : '') . 'Added to waiting list — batch is full.')
                : $request->admin_notes,
        ]);

        $message = $isWaitlisted
            ? "Candidate approved and added to waiting list for the selected batch. Code: {$uniqueCode}"
            : "Candidate approved successfully. Unique Code: {$uniqueCode}";

        return back()->with('success', $message);
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
