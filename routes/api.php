<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/checkout/{invoice}', [InvoiceController::class, 'checkout'])->name('api.invoice.checkout');

// Route::get('/api/invoice/{invoice}', [InvoiceController::class, 'generateMidtrans'])->name('api.invoice');
// Route::post('/api/callback', [OrderController::class, 'callback']);
