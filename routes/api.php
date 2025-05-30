<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LearningModuleController;
use App\Http\Controllers\ModuleController;

Route::middleware('api')->group(function () {
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('learning-modules', LearningModuleController::class);
    
    // Module routes
    Route::apiResource('modules', ModuleController::class);
    Route::get('trashed-modules', [ModuleController::class, 'getTrashed']);
    Route::post('modules/{id}/restore', [ModuleController::class, 'restore']);
    Route::delete('modules/{id}/force', [ModuleController::class, 'forceDelete']);
}); 