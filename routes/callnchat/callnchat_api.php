<?php

use App\Http\Controllers\CallnChat\Api\AuthController;
use App\Http\Controllers\CallnChat\Api\CallController;
use App\Http\Controllers\CallnChat\Api\ChatBotController;
use App\Http\Controllers\CallnChat\Api\ChatBotProductController;
use App\Http\Controllers\CallnChat\Api\ChatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CallnChat\Api\HelpdeskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*
 | --------------------------------------------------------------------------
 | CALL & CHAT WEB EMBED API ROUTING URL
 | --------------------------------------------------------------------------
 */

Route::controller(HelpdeskController::class)
     ->prefix('helpdesk')
     ->as('helpdesk.')
     ->group(function () {
          Route::get('product', 'product')->name('product-list');
          Route::get('faq', 'faq')->name('faq-list');
          Route::get('faq/top', 'topFaq')->name('top-faq');
          Route::get('faq/{id}', 'detailFaq')->name('detail-faq');
          Route::get('helpdesk-category', 'helpdeskCategory')->name('helpdesk-category-list');
          Route::get('agent/{id}/{category}', 'agent')->name('agent-category-list');
          Route::get('agent/{id}', 'detailAgent')->name('detail-agent');
          Route::get('helpdesk-list/{id}/{category}', 'helpdeskList')->name('helpdesk-list');
          Route::get('agent/available/{id}/{category}', 'availableAgent')->name('available-agent');
     });

Route::controller(ChatController::class)
     ->prefix('chat/message')
     ->as('chat-message.')
     ->group(function () {
          Route::get('/{id}', 'index')->name('index');
          Route::post('store/{category}', 'store')->whereIn('category', ['message', 'file', 'location'])->name('store');
          Route::post('{id}/{agent_id}/end', 'destroy')->name('destroy');
          Route::get('{ticket_id}/csat-template', 'csatTemplate')->name('csat-template');
     });

Route::controller(CallController::class)
     ->prefix('call')
     ->as('call.')
     ->group(function () {
          Route::post('invite-agent/{agent_id}', 'invite')->name('invite');
          Route::post('do/{action}/{id}', 'actionCall')
               ->whereIn('action', ['accept', 'reject', 'cancel', 'end', 'missed', 'disconnect', 'spv-join', 'spv-leave', 'spv-reject'])
               ->name('action-cal');
          Route::post('rating', 'rating')->name('rating');
          Route::post('request-callback', 'requestCallback')->name('request-callback');
     });

Route::controller(ChatBotController::class)
     ->prefix('chat/bot')
     ->as('chat-bot.')
     ->group(function () {
          Route::get('/product/{category}', 'allProduct')->name('product-v2');
          Route::get('/{id}/conversation', 'index')->name('index');
          Route::get('/{id}/product', 'product')->name('product');
          Route::get('/phone-code', 'phoneCode')->name('phone-code');
          Route::post('start', 'start')->name('start');
          Route::post('{id}/next', 'next')->name('next');
          Route::post('{id}/end', 'end')->name('end');
          Route::post('{id}/send-message', 'sendMessage')->name('send-message');
          Route::post('{id}/response-message', 'responseMessage')->name('response-message');
          Route::post('{id}/booking', 'booking')->name('booking');
          Route::post('{id}/resend-otp', 'resendOtp')->name('resend-otp');
          Route::post('{id}/validate-otp', 'validateOtp')->name('validate-otp');
     });

Route::controller(ChatBotProductController::class)
     ->prefix('chat/bot-product')
     ->as('chat-bot-product.')
     ->group(function () {
          Route::post('choose-product', 'chooseProduct')->name('choose-product');
          Route::post('request-otp', 'requestOtp')->name('request-otp');
          Route::post('validate-otp', 'validateOtp')->name('validate-otp');
          Route::post('booking', 'booking')->name('booking');
          Route::get('location/{category}', 'scheduleLocation')->name('schedule-location');
          Route::get('professional-name/{category}', 'scheduleProfessionalName')->name('schedule-professional-name');
          Route::get('pic-name/{category}', 'schedulePicName')->name('schedule-pic-name');
          Route::get('schedule-date/{category}', 'scheduleDate')->name('schedule-date');
     });

Route::controller(AuthController::class)
     ->prefix('auth')
     ->as('auth.')
     ->group(function () {
          Route::post('login', 'login')->name('login');
     });
