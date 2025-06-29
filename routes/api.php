<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Domain\Contact\Http\Controllers\V1\ContactController;
use Illuminate\Validation\ValidationException; 
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Http\Requests\TestRequest;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('v1')->group(function () {
    Route::prefix('contacts')->group(function () {
        Route::get('/', [ContactController::class, 'index']); 
        Route::post('/', [ContactController::class, 'store']);
        Route::get('/{id}', [ContactController::class, 'show']);
        Route::delete('/{id}', [ContactController::class, 'destroy']);
        Route::post('/{id}/call', [ContactController::class, 'call']);
    });

    Route::get('/ping', fn () => response()->json(['pong' => true]));
});


