<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Module::all();
        return response()->json($modules);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string',
            'note' => 'nullable|string',
            'category_color' => 'nullable|string',
            'deadline' => 'nullable|date',
            'file' => 'nullable|file|max:10240', // 10MB max
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('modules', 'public');
            $validated['file_path'] = $path;
        }

        $module = Module::create($validated);
        return response()->json($module, 201);
    }

    public function show(Module $module)
    {
        return response()->json($module);
    }

    public function update(Request $request, Module $module)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string',
            'note' => 'nullable|string',
            'category_color' => 'nullable|string',
            'deadline' => 'nullable|date',
            'file' => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('file')) {
            if ($module->file_path) {
                Storage::disk('public')->delete($module->file_path);
            }
            $path = $request->file('file')->store('modules', 'public');
            $validated['file_path'] = $path;
        }

        $module->update($validated);
        return response()->json($module);
    }

    public function destroy(Module $module)
    {
        $module->delete(); // This will soft delete the module
        return response()->json(null, 204);
    }

    public function getTrashed()
    {
        $trashedModules = Module::onlyTrashed()->get();
        return response()->json($trashedModules);
    }

    public function restore($id)
    {
        $module = Module::onlyTrashed()->findOrFail($id);
        $module->restore();
        return response()->json($module);
    }

    public function forceDelete($id)
    {
        $module = Module::onlyTrashed()->findOrFail($id);
        if ($module->file_path) {
            Storage::disk('public')->delete($module->file_path);
        }
        $module->forceDelete();
        return response()->json(null, 204);
    }
} 