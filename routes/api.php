<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;

Route::post('/api/payment/callback', [PaymentController::class, 'handleCallback']);
