<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HousePreviewController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('house-previews')->group(function () {
    Route::get('/', [HousePreviewController::class, 'index']);
    Route::post('/', [HousePreviewController::class, 'store']);
    Route::get('/{id}', [HousePreviewController::class, 'show']);
    Route::put('/{id}', [HousePreviewController::class, 'update']);
    Route::delete('/{id}', [HousePreviewController::class, 'destroy']);
});
