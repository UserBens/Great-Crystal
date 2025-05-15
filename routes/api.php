<?php

use App\Http\Controllers\API\BillController;
use App\Http\Controllers\API\PaymentStatusController;
use App\Http\Controllers\UserController;
use App\Models\Bill;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [UserController::class, 'login']);

Route::get('/check-payment-status/{unique_id}', [PaymentStatusController::class, 'checkStatus']);

Route::get('/payment-history/{unique_id}', [PaymentStatusController::class, 'getPaymentHistory']);

Route::get('/payment-detail/{unique_id}/{bill_id}', [PaymentStatusController::class, 'getPaymentDetail']);