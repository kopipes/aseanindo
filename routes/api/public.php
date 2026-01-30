<?php

use App\Http\Controllers\Api\Follower\ApiFollowerController;
use App\Http\Controllers\Api\Helpdesk\ApiHelpdeskController;
use App\Http\Controllers\Api\Product\ApiProductController;
use App\Http\Controllers\Api\Rating\ApiRatingController;
use App\Http\Controllers\Api\User\ApiSearchAccountController;
use App\Http\Controllers\Api\Util\ApiSettingController;
use App\Http\Controllers\Api\Util\ApiUtilController;
use Illuminate\Support\Facades\Route;

Route::get('/',function(){
     return 'Hello :)';
})->name('hello');

Route::controller(ApiSettingController::class)
     ->group(function () {
          Route::get('/settings', 'index')->name('setting.index');
          Route::get('/document/{key}', 'document')
               ->name('document.index')
               ->whereIn('key', ['term-condition', 'privacy-policies', 'about-us']);
     });


Route::controller(ApiUtilController::class)
     ->prefix('util')
     ->as('util.')
     ->group(function () {
          Route::get('/faq', 'faq')->name('faq');
          Route::get('/slider', 'slider')->name('slider');
          Route::get('/company-category', 'companyCategory')->name('company-category');
          Route::get('/report-reason/{type}', 'reportReason')->name('report-reason')->whereIn('type',['product','account']);
     });

Route::controller(ApiProductController::class)
     ->prefix('product')
     ->as('product.')
     ->group(function () {
          Route::get('/', 'index')->name('index');
          Route::get('/show/{id}', 'show')->name('show');
          Route::get('/company/{id}', 'productCompany')->name('company');
     });



Route::controller(ApiSearchAccountController::class)
     ->prefix('account')
     ->as('account.')
     ->group(function () {
          Route::get('/search', 'index')->name('search.index');
          Route::get('/username/{type}', 'username')->name('search.username');
          Route::get('/customer/{id}', 'showCustomer')->name('show.customer');
          Route::get('/company/{id}', 'showCompany')->name('show.company');
     });

Route::controller(ApiFollowerController::class)
     ->prefix('follower')
     ->as('follower.')
     ->group(function () {
          Route::get('/company/{id}', 'followerCompany')->name('show.company');
          Route::get('/customer/{id}', 'followedCustomer')->name('show.customer');
     });

Route::controller(ApiHelpdeskController::class)
     ->prefix('helpdesk')
     ->as('helpdesk.')
     ->group(function () {
          Route::get('/company/{id}', 'index')->name('index');
          Route::get('/office-hour/{id}', 'officeHour')->name('office-hour');
          Route::get('/company/{id}/agent', 'agent')->name('agent');
     });
Route::get('/rating/company/{id}', [ApiRatingController::class, 'ratingsCompany'])->name('rating.list-rating-company');