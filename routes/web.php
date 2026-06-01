<?php

use App\Http\Controllers\CrmDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/crm');
});

Route::get('/crm', [CrmDashboardController::class, 'index']);
Route::post('/crm/accounts', [CrmDashboardController::class, 'storeAccount']);
Route::post('/crm/leads', [CrmDashboardController::class, 'storeLead']);
