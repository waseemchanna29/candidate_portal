<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Course;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index()
    {
        $batches = Batch::with('course')->withCount('candidates')->latest()->paginate(15);
        return view('admin.batches.index', compact('batches'));
    }

    public function create()
    {
        $courses = Course::where('is_active', true)->orderBy('name')->get();
        return view('admin.batches.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id'   => ['required', 'exists:courses,id'],
            'course_code' => ['required', 'string', 'max:20'],
            'year'        => ['required', 'integer', 'min:2000', 'max:' . (date('Y') + 5)],
            'batch_no'    => ['required', 'integer', 'min:1'],
            'total_seats' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:open,full,in_progress,closed'],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'notes'       => ['nullable', 'string', 'max:1000'],
        ]);

        // Check unique batch per course/year/batch_no
        $exists = Batch::where('course_id', $request->course_id)
            ->where('year', $request->year)
            ->where('batch_no', $request->batch_no)
            ->exists();

        if ($exists) {
            return back()->withErrors(['batch_no' => 'A batch with this number already exists for this course and year.'])->withInput();
        }

        Batch::create($request->only([
            'course_id',
            'course_code',
            'year',
            'batch_no',
            'total_seats',
            'status',
            'start_date',
            'end_date',
            'notes'
        ]));

        return redirect()->route('admin.batches.index')
            ->with('success', 'Batch created successfully.');
    }

    public function show(Batch $batch)
    {
        $batch->load('course');
        $enrolled   = $batch->approvedCandidates()->with('user')->get();
        $waitlisted = $batch->waitlistedCandidates()->with('user')->get();
        $pending    = $batch->candidates()->where('status', 'pending')->with('user')->get();
        return view('admin.batches.show', compact('batch', 'enrolled', 'waitlisted', 'pending'));
    }

    public function edit(Batch $batch)
    {
        $courses = Course::where('is_active', true)->orderBy('name')->get();
        return view('admin.batches.edit', compact('batch', 'courses'));
    }

    public function update(Request $request, Batch $batch)
    {
        $request->validate([
            'course_code' => ['required', 'string', 'max:20'],
            'year'        => ['required', 'integer', 'min:2000', 'max:' . (date('Y') + 5)],
            'batch_no'    => ['required', 'integer', 'min:1'],
            'total_seats' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:open,full,in_progress,closed'],
            'start_date'  => ['nullable', 'date'],
            'end_date'    => ['nullable', 'date', 'after_or_equal:start_date'],
            'notes'       => ['nullable', 'string', 'max:1000'],
        ]);

        $batch->update($request->only([
            'course_code',
            'year',
            'batch_no',
            'total_seats',
            'status',
            'start_date',
            'end_date',
            'notes'
        ]));

        return redirect()->route('admin.batches.index')
            ->with('success', "Batch \"{$batch->batch_label}\" updated successfully.");
    }

    public function destroy(Batch $batch)
    {
        if ($batch->candidates()->count() > 0) {
            return back()->with('error', 'Cannot delete batch — it has assigned candidates.');
        }

        $batch->delete();
        return redirect()->route('admin.batches.index')
            ->with('success', 'Batch deleted successfully.');
    }

    public function addSeats(Request $request, Batch $batch)
    {
        $request->validate([
            'seats' => ['required', 'integer', 'min:1', 'max:500'],
        ]);

        $batch->increment('total_seats', $request->seats);

        // Auto reopen if was full and now has seats
        if ($batch->status === 'full' && $batch->seats_available > 0) {
            $batch->update(['status' => 'open']);
        }

        return back()->with('success', "{$request->seats} seat(s) added. Total seats: {$batch->total_seats}.");
    }

    public function promoteFromWaitlist(Request $request, Batch $batch, \App\Models\Candidate $candidate)
    {
        if ($batch->seats_available <= 0) {
            return back()->with('error', 'No seats available to promote this candidate.');
        }

        if (!$candidate->is_waitlisted || $candidate->batch_id !== $batch->id) {
            return back()->with('error', 'Candidate is not on the waiting list for this batch.');
        }

        $candidate->update(['is_waitlisted' => false]);

        // Auto-mark batch full if seats run out
        if ($batch->seats_available - 1 <= 0) {
            $batch->update(['status' => 'full']);
        }

        return back()->with('success', "{$candidate->full_name} has been promoted from the waiting list.");
    }
}
