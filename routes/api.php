<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\LeadController;
use Illuminate\Support\Facades\Route;

Route::post('/accounts', [AccountController::class, 'store']);
Route::post('/leads', [LeadController::class, 'store']);
