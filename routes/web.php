<?php

use App\Http\Controllers\ContactController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\LeadController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/crm', [ CrmController::class, 'index' ])->name('crm.index');

    Route::get('/leads/{lead}', [ LeadController::class, 'show' ])->name('leads.show');
    Route::post('/leads', [ LeadController::class, 'store' ])->name('leads.store');
    Route::put('/leads/{lead}', [ LeadController::class, 'update' ])->name('leads.update');
    Route::delete('/leads/{lead}', [ LeadController::class, 'destroy' ])->name('leads.destroy');

    Route::post('/leads-contact-create-update/{lead}', [ ContactController::class, 'createOrUpdateContactLead' ])->name('leads.contact.create.update');
});
