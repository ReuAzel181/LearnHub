<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LearningModuleController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\Api\HeaderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api')->group(function () {
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('learning-modules', LearningModuleController::class);
    
    // Module routes
    Route::apiResource('modules', ModuleController::class);
    Route::get('trashed-modules', [ModuleController::class, 'getTrashed']);
    Route::post('modules/{id}/restore', [ModuleController::class, 'restore']);
    Route::delete('modules/{id}/force', [ModuleController::class, 'forceDelete']);
    
    // Header functionality routes
    Route::get('/search', [HeaderController::class, 'search']);
    Route::get('/notifications', [HeaderController::class, 'notifications']);
    Route::post('/notifications/mark-read', [HeaderController::class, 'markNotificationsAsRead']);
    
    // Error logging route
    Route::post('/log-error', function (Request $request) {
        \Log::error('Frontend Error', [
            'component' => $request->input('component'),
            'action' => $request->input('action'),
            'error' => $request->input('error'),
            'stack' => $request->input('stack'),
            'user_agent' => $request->header('User-Agent'),
            'ip' => $request->ip()
        ]);
        
        return response()->json(['status' => 'logged']);
    });
}); 