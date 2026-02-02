<?php

use App\Http\Controllers\Api\Call\ApiCallController;
use App\Http\Controllers\Api\Chat\ApiChatController;
use App\Http\Controllers\Api\Follower\ApiFollowerController;
use App\Http\Controllers\Api\Inbox\ApiInboxController;
use App\Http\Controllers\Api\Product\ApiProductController;
use App\Http\Controllers\Api\Profile\ApiEditProfileController;
use App\Http\Controllers\Api\Profile\ApiProfileController;
use App\Http\Controllers\Api\Rating\ApiRatingController;
use App\Http\Controllers\Api\User\ApiAccountController;
use Illuminate\Support\Facades\Route;


Route::controller(ApiEditProfileController::class)
     ->prefix('profile/edit')
     ->as('profile.edit.')
     ->group(function () {
          Route::post('/phone/send-otp', 'sendOtpEditPhone')->name('send-otp.edit-phone');
          Route::post('/phone', 'updatePhoneNumber')->name('update-phone');

          Route::post('/email/send-otp', 'sendOtpEditEmail')->name('send-otp.edit-email');
          Route::post('/email', 'updateEmailAddress')->name('update-email');
     });


Route::controller(ApiProfileController::class)
     ->as('profile.')
     ->prefix('profile')
     ->group(function () {
          Route::get('/', 'index')->name('index');
          Route::get('/blocked-account', 'blockedAccount')->name('blocked-account');
          Route::post('/update', 'updateProfile')->name('update.profile');
          Route::post('/update/lang', 'updateLang')->name('update.lang');
          Route::post('/update/bio', 'updateBio')->name('update.bio');
          Route::post('/update/password', 'updatePassword')->name('update.password');
          Route::delete('/', 'deleteAccount')->name('delete.account');
          Route::delete('/logout', 'logout')->name('logout');
     });


Route::controller(ApiFollowerController::class)
     ->prefix('follower')
     ->as('follower.')
     ->group(function () {
          Route::post('/company/{id}/{type}', 'followCompany')->name('follow.company')->whereIn('type', ['follow', 'un-follow']);
     });

Route::controller(ApiProductController::class)
     ->prefix('product')
     ->as('product.')
     ->group(function () {
          Route::get('/following', 'followingProduct')->name('following');
          Route::get('/favorite', 'favoriteProduct')->name('favorite');
          Route::post('/{id}/report', 'report')->name('report');
          Route::post('/{product_id}/{company_id}/favorite', 'love')->name('love');
     });

Route::controller(ApiAccountController::class)
     ->prefix('account')
     ->as('account.')
     ->group(function () {
          Route::post('/report/{type}/{id}', 'reportAccount')->name('report.ba')->whereIn('type', ['customer', 'company']);
          Route::post('/block/{type}/{id}', 'blockAccount')->name('block.ba')->whereIn('type', ['customer', 'company']);
          Route::post('/un-block/{type}/{id}', 'unBlockAccount')->name('un-block.ba')->whereIn('type', ['customer', 'company']);
     });


Route::controller(ApiChatController::class)
     ->prefix('chat')
     ->as('chat.')
     ->group(function () {
          Route::get('/history', 'index')->name('history');
          Route::get('/active', 'active')->name('active');
          Route::get('/{id}/conversation', 'conversation')->name('conversation');
          Route::post('/message/send', 'sendChatMessage')->name('message.send');
          Route::post('/{id}/end', 'endChat')->name('end');
          Route::post('/{id}/end-by-agent', 'endChatByAgent')->name('end-by-agent');
     });


Route::controller(ApiCallController::class)
     ->prefix('call')
     ->as('call.')
     ->group(function () {
          Route::get('/history', 'index')->name('history');
          Route::get('/{id}', 'show')->name('show');
          Route::post('/{company_id}/request-callback', 'requestCallback')->name('request-callback');
          Route::post('/agent/{agent_id}/{company_id}', 'inviteAgent')->name('call-agent');
          Route::post('/customer/{customer_id}', 'callCustomer')->name('call-customer');
          Route::post('/{call_id}/{action}', 'callActionDo')->name('call-action')->whereIn('action', [
               'accept',
               'reject',
               'cancel',
               'end',
               'missed',
               'disconnect',
               'spv-join',
               'spv-leave',
               'spv-reject',
               'invite-spv'
          ]);
     });

Route::controller(ApiInboxController::class)
     ->prefix('inbox')
     ->as('inbox.')
     ->group(function () {
          Route::get('/', 'index')->name('index');
          Route::get('/un-read', 'count')->name('count');
          Route::get('/{id}/read', 'read')->name('read');
          Route::delete('/{id}', 'destroy')->name('destroy');
     });

Route::controller(ApiRatingController::class)
     ->prefix('rating')
     ->as('rating.')
     ->group(function () {
          Route::get('/{id}', 'show')->name('show');
          Route::post('/{id}/review', 'review')->name('review');
          Route::post('/{ticket_id}/review', 'reviewCallOrChat')->name('review-call-chat')
               ->whereIn('category', ['chat', 'call']);
     });
