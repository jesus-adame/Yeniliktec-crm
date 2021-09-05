<?php

use App\Http\Controllers\AdsGoogleController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CrmController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\MailLeadsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\WhatsAppController;

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

    Route::get('/lead-quote/create/{lead}',  [ CrmController::class, 'createQuote' ])->name('crm.create.quote');
    Route::get('/lead-appointment/create/{lead}',  [ CrmController::class, 'createAppointment' ])->name('crm.create.appointment');

    Route::post('/leads-contact-create-update/{lead}', [ ContactController::class, 'createOrUpdateContactLead' ])->name('leads.contact.create.update');
    Route::post('/lead-document/{lead}',    [ LeadController::class, 'addDocument' ])->name('lead.document');

    Route::get('/inbox',    [ InboxController::class, 'index' ])->name('inbox.cur');
    Route::get('/imap-messages', [ InboxController::class, 'getMessages' ])->name('inbox.messages');
    Route::get('/imap-messages/{message}', [ InboxController::class, 'show' ])->name('inbox.show');

    Route::post('/email-leads/{message}', [ MailLeadsController::class, 'createLead' ])->name('emails.leads.store');

    Route::get('/quotes',               [ QuotationController::class, 'index' ])    ->name('quotes.index');
    Route::get('/quotes/create',        [ QuotationController::class, 'create' ])   ->name('quotes.create');
    Route::post('/quotes',              [ QuotationController::class, 'store' ])    ->name('quotes.store');
    Route::get('/quotes/{quote}/edit',  [ QuotationController::class, 'edit' ])     ->name('quotes.edit');
    Route::put('/quotes/{quote}',       [ QuotationController::class, 'update' ])   ->name('quotes.update');
    Route::delete('/quotes/{quote}',    [ QuotationController::class, 'destroy' ])  ->name('quotes.destroy');

    Route::get('/appointments',     [ AppointmentController::class, 'index' ])->name('appointments.index');
    Route::post('/appointments',    [ AppointmentController::class, 'store' ])->name('appointments.store');
    Route::get('/appointments/{appointment}/edit', [ AppointmentController::class, 'edit' ])->name('appointments.edit');
    Route::put('/appointments/{appointment}',    [ AppointmentController::class, 'update' ])->name('appointments.update');
    Route::delete('/appointments/{appointment}',  [ AppointmentController::class, 'destroy' ])->name('appointments.destroy');

    Route::get('/documents', [ DocumentController::class, 'index' ])->name('documents.index');
    Route::get('/documents/create', [ DocumentController::class, 'create' ])->name('documents.create');
    Route::post('/documents', [ DocumentController::class, 'store' ])->name('documents.store');
    Route::get('/documents/{document}/edit', [ DocumentController::class, 'edit' ])->name('documents.edit');
    Route::put('/documents/{document}', [ DocumentController::class, 'update' ])->name('documents.update');
    Route::delete('/documents/{document}', [ DocumentController::class, 'destroy' ])->name('documents.destroy');
    Route::get('/documents-download/{document}', [ DocumentController::class, 'download' ])->name('documents.download');

    Route::get('/print/quote/{quote}', [ QuotationController::class, 'printQuote' ])->name('print.quote');

    Route::get('/columns',              [ ColumnController::class, 'index' ])->name('columns.index');
    Route::get('/columns/create',       [ ColumnController::class, 'create' ])->name('columns.create');
    Route::post('/columns',             [ ColumnController::class, 'store' ])->name('columns.store');
    Route::get('/columns/{column}/edit', [ ColumnController::class, 'edit' ])->name('columns.edit');
    Route::put('/columns/{column}',     [ ColumnController::class, 'update' ])->name('columns.update');
    Route::delete('/columns/{column}',  [ ColumnController::class, 'destroy' ])->name('columns.destroy');
});

Route::get('/whatsapp/send/{number}', [ WhatsAppController::class, 'sendMessage' ]);