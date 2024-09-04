<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OpeningController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/register/student', [
    StudentController::class,
    'register'
]);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/student/openings', [
        StudentController::class,
        'index'
    ]);
    Route::post(
        '/student/openings/{id}/apply',
        [StudentController::class, 'apply']
    );
    Route::delete('/student/profile', [
        StudentController::class,
        'destroy'
    ]);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::resource('openings', OpeningController::class)->except(['edit', 'create']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/company/profile', [CompanyController::class, 'destroy']);
    Route::get('/companies', [CompanyController::class, 'index']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::put('/applications/{applicationId}/status', [ApplicationController::class, 'updateStatus']);
    Route::get('/applications/openings/{openingId}', [ApplicationController::class, 'indexForOpening']);
    Route::get('/applications', [ApplicationController::class, 'indexForAdmin']);
    Route::delete('/applications/{applicationId}', [ApplicationController::class, 'destroy']);
});

