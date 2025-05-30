<?php

namespace App\Http\Controllers;

use App\Models\LearningModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LearningModuleController extends Controller
{
    public function index()
    {
        $modules = LearningModule::with('category')->get();
        return response()->json($modules);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'deadline' => 'nullable|date',
            'file' => 'nullable|file|max:10240', // 10MB max
        ]);

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('modules', 'public');
            $validated['file_path'] = $path;
        }

        $module = LearningModule::create($validated);
        return response()->json($module, 201);
    }

    public function show(LearningModule $learningModule)
    {
        return response()->json($learningModule->load('category'));
    }

    public function update(Request $request, LearningModule $learningModule)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'deadline' => 'nullable|date',
            'file' => 'nullable|file|max:10240', // 10MB max
        ]);

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($learningModule->file_path) {
                Storage::disk('public')->delete($learningModule->file_path);
            }
            
            $path = $request->file('file')->store('modules', 'public');
            $validated['file_path'] = $path;
        }

        $learningModule->update($validated);
        return response()->json($learningModule);
    }

    public function destroy(LearningModule $learningModule)
    {
        if ($learningModule->file_path) {
            Storage::disk('public')->delete($learningModule->file_path);
        }
        
        $learningModule->delete();
        return response()->json(null, 204);
    }
} 