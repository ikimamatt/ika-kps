<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/daftar-alumni', [RegistrationController::class, 'store'])->name('alumni.register');
