<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\OpeningController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Autentifikacija
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/register/student', [StudentController::class,'register']);
Route::post('/register/company', [CompanyController::class,'register']);

Route::middleware('auth:sanctum')->group(function () {
    // Student rute
    Route::post('/student/openings/{id}/apply',[StudentController::class, 'apply']); // Prijava studenta na oglas
    Route::delete('/student/profile/{studentId?}', [StudentController::class, 'destroy']); // Brisanje studenta (sopstvenog naloga ukoliko nije prosledjen ID ili odredjenog naloga od strane admina ukoliko je prosledjen ID)

    // Kompanija rute
    Route::delete('/company/profile/{companyId?}', [CompanyController::class, 'destroy']); // // Brisanje kompanije (sopstvenog naloga ukoliko nije prosledjen ID ili odredjenog naloga od strane admina ukoliko je prosledjen ID)
    Route::get('/companies', [CompanyController::class, 'index']); // Prikaz svih kompanija

    // Poslovi rute
    Route::resource('openings', OpeningController::class)->except(['edit', 'create']); // Resource API za poslove
    Route::get('/company/openings', [OpeningController::class, 'companyOpenings']); // Prikaz svih poslova kompanije

    // Prijava rute
    Route::put('/applications/{applicationId}/status', [ApplicationController::class, 'updateStatus']); // Promena statusa prijave
    Route::get('/applications/openings/{openingId}', [ApplicationController::class, 'indexForOpening']); // Prikaz prijava na odredjeni oglas kompanije
    Route::get('/applications', [ApplicationController::class, 'indexForAdmin']); // Prikaz svih prijava (namenjen adminu)
    Route::delete('/applications/{applicationId}', [ApplicationController::class, 'destroy']); // Brisanje prijave (namenjeno adminu)
    
});

