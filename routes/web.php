<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QuotationController;

Route::redirect('/', '/login', 301);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', [ HomeController::class, 'dashboard' ])->name('dashboard');

    Route::get('/crm', [ CrmController::class, 'index' ])->name('crm.index');

    Route::get('/productos',                [ ProductController::class, 'index' ])  ->name('products.index');
    Route::get('/productos/crear',          [ ProductController::class, 'create' ]) ->name('products.create');
    Route::post('/productos',               [ ProductController::class, 'store' ])  ->name('products.store');
    Route::get('/productos/{product}/edit', [ ProductController::class, 'edit' ])   ->name('products.edit');
    Route::put('/productos/{product}',      [ ProductController::class, 'update' ]) ->name('products.update');
    Route::delete('/productos/{product}',   [ ProductController::class, 'destroy' ])->name('products.destroy');

    Route::get('/leads/{lead}',     [ LeadController::class, 'show' ])->name('leads.show');
    Route::post('/leads',           [ LeadController::class, 'store' ])->name('leads.store');
    Route::put('/leads/{lead}',     [ LeadController::class, 'update' ])->name('leads.update');
    Route::delete('/leads/{lead}',  [ LeadController::class, 'destroy' ])->name('leads.destroy');

    Route::post('/leads-contact-create-update/{lead}', [ ContactController::class, 'createOrUpdateContactLead' ])->name('leads.contact.create.update');

    Route::get('/inbox',    [ InboxController::class, 'index' ])->name('inbox.cur');

    Route::get('/quotes',               [ QuotationController::class, 'index' ])    ->name('quotes.index');
    Route::get('/quotes/create',        [ QuotationController::class, 'create' ])   ->name('quotes.create');
    Route::post('/quotes',              [ QuotationController::class, 'store' ])    ->name('quotes.store');
    Route::get('/quotes/{quote}/edit',  [ QuotationController::class, 'edit' ])     ->name('quotes.edit');
    Route::put('/quotes/{quote}',       [ QuotationController::class, 'update' ])   ->name('quotes.update');
    Route::delete('/quotes/{quote}',    [ QuotationController::class, 'destroy' ])  ->name('quotes.destroy');
});
