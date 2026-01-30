<?php

use App\Http\Controllers\Api\Auth\ApiLoginController;
use App\Http\Controllers\Api\Auth\ApiRegisterController;
use App\Http\Controllers\Api\Auth\ApiResetPasswordController;
use App\Http\Controllers\Api\Auth\ApiTokenController;
use Illuminate\Support\Facades\Route;



Route::controller(ApiTokenController::class)
     ->prefix('token')
     ->as('auth.token.')
     ->group(function () {
          Route::get('/', 'getToken')->name('get');
          Route::get('/renew', 'renewToken')->name('renew');
     });

Route::controller(ApiRegisterController::class)
     ->prefix('auth/register')
     ->as('auth.register.')
     ->group(function () {
          Route::post('/validate', 'validateData')->name('validate');
          Route::post('/validate-phone', 'validatePhone')->name('validate-phone');
          Route::post('/otp/send', 'sendOtp')->name('send-otp');
          Route::post('/otp/validate', 'validateOtp')->name('validate-otp');
          Route::post('/', 'store')->name('store');
     });

Route::controller(ApiResetPasswordController::class)
     ->prefix('auth/reset-password')
     ->as('auth.reset-password.')
     ->group(function () {
          Route::post('/otp/send', 'sendOtp')->name('send-otp');
          Route::post('/otp/validate', 'validateOtp')->name('validate-otp');
          Route::post('/', 'store')->name('store');
     });

Route::post('/auth/login', ApiLoginController::class)->name('auth.login');
