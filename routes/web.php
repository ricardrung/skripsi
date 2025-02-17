<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('pages.home.home');
});

// Route::get('/navbar', function () {
//     return view('components.navbar.navbar');
// });
// Route::get('/footer', function () {
//     return view('components.footer.footer');
// });
// Route::get('/layout', function () {
//     return view('components.layout.layout');
// });

Route::get('/login',[AuthController::class, 'login'])->name('login');
Route::get('/signup',[AuthController::class, 'signup'])->name('signup');
Route::get('/resetpassword',[AuthController::class, 'resetpassword'])->name('resetpassword');