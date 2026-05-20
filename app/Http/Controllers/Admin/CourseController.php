<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\PricingModel;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::withCount('candidates')->latest()->paginate(15);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $pricingModels = PricingModel::where('is_active', true)->orderBy('name')->get();
        return view('admin.courses.create', compact('pricingModels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => ['required', 'string', 'max:255', 'unique:courses,name'],
            'pricing_model_id' => ['nullable', 'exists:pricing_models,id'],
            'duration_months' => ['required', 'integer', 'min:1', 'max:120'],
            'description'     => ['nullable', 'string', 'max:1000'],
            'is_active'       => ['nullable', 'boolean'],
        ]);

        Course::create([
            'name'            => $request->name,
            'duration_months' => $request->duration_months,
            'description'     => $request->description,
            'is_active'       => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.courses.index')
            ->with('success', "Course \"{$request->name}\" created successfully.");
    }

    public function show(Course $course)
    {
        $course->loadCount('candidates');
        $course->load(['candidates' => fn($q) => $q->with('user')->latest()->take(10), 'pricingModel']);

        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $pricingModels = PricingModel::where('is_active', true)->orderBy('name')->get();
        return view('admin.courses.edit', compact('course', 'pricingModels'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'name'            => ['required', 'string', 'max:255', 'unique:courses,name,' . $course->id],
            'pricing_model_id' => ['nullable', 'exists:pricing_models,id'],
            'duration_months' => ['required', 'integer', 'min:1', 'max:120'],
            'description'     => ['nullable', 'string', 'max:1000'],
            'is_active'       => ['nullable', 'boolean'],
        ]);

        $course->update([
            'name'            => $request->name,
            'duration_months' => $request->duration_months,
            'description'     => $request->description,
            'is_active'       => $request->boolean('is_active'),
            'pricing_model_id' => $request->pricing_model_id ?: null, 
        ]);

        return redirect()->route('admin.courses.index')
            ->with('success', "Course \"{$course->name}\" updated successfully.");
    }

    public function destroy(Course $course)
    {
        $name = $course->name;

        if ($course->candidates()->count() > 0) {
            return back()->with('error', "Cannot delete \"{$name}\" — it has enrolled candidates.");
        }

        $course->delete();
        return redirect()->route('admin.courses.index')
            ->with('success', "Course \"{$name}\" deleted successfully.");
    }

    public function toggleStatus(Course $course)
    {
        $course->update(['is_active' => !$course->is_active]);
        $label = $course->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Course \"{$course->name}\" has been {$label}.");
    }
}
