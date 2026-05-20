<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingModel;
use Illuminate\Http\Request;

class PricingModelController extends Controller
{
    public function index()
    {
        $pricingModels = PricingModel::withCount('courses')->latest()->paginate(15);
        return view('admin.pricing_models.index', compact('pricingModels'));
    }

    public function create()
    {
        return view('admin.pricing_models.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:pricing_models,name'],
            'price'       => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        PricingModel::create([
            'name'        => $request->name,
            'price'       => $request->price,
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.pricing-models.index')
                         ->with('success', "Pricing model \"{$request->name}\" created successfully.");
    }

    public function show(PricingModel $pricingModel)
    {
        $pricingModel->loadCount('courses');
        $pricingModel->load('courses');
        return view('admin.pricing_models.show', compact('pricingModel'));
    }

    public function edit(PricingModel $pricingModel)
    {
        return view('admin.pricing_models.edit', compact('pricingModel'));
    }

    public function update(Request $request, PricingModel $pricingModel)
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:pricing_models,name,' . $pricingModel->id],
            'price'       => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        $pricingModel->update([
            'name'        => $request->name,
            'price'       => $request->price,
            'description' => $request->description,
            'is_active'   => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.pricing-models.index')
                         ->with('success', "Pricing model \"{$pricingModel->name}\" updated successfully.");
    }

    public function destroy(PricingModel $pricingModel)
    {
        $name = $pricingModel->name;

        if ($pricingModel->courses()->count() > 0) {
            return back()->with('error', "Cannot delete \"{$name}\" — it is assigned to one or more courses.");
        }

        $pricingModel->delete();
        return redirect()->route('admin.pricing-models.index')
                         ->with('success', "Pricing model \"{$name}\" deleted successfully.");
    }

    public function toggleStatus(PricingModel $pricingModel)
    {
        $pricingModel->update(['is_active' => !$pricingModel->is_active]);
        $label = $pricingModel->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Pricing model \"{$pricingModel->name}\" has been {$label}.");
    }
}