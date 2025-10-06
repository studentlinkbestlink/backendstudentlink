<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    try {
        return response()->json([
            'message' => 'StudentLink Backend API',
            'version' => '1.0.0',
            'status' => 'active',
            'timestamp' => now(),
            'environment' => app()->environment()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Application error',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
});

// Health check endpoint for Render
Route::get('/healthz', function () {
    return response('OK', 200);
});

// Simple test endpoint without database
Route::get('/test', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'Backend is working',
        'timestamp' => date('Y-m-d H:i:s'),
        'php_version' => PHP_VERSION
    ]);
});