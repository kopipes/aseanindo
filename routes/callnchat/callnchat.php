<?php

/**
 * IMPORT CONTROLLER CLASS FOR THE ROUTE
 * MAKE SURE YOU ONLY ADD FOR YOUR OWN ROUTE
 * DO NOT DELETE EXISTING ONE IF YOU DON'T KNOW ITS FUNCTION
 */
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPage\BlogController;
use App\Http\Controllers\LandingPage\LandingPageController;
use App\Http\Controllers\CallnChat\CallnChatIndexController;
use App\Http\Controllers\CallnChat\CallnChatConfigController;

/*
 | --------------------------------------------------------------------------
 | LANDING PAGE ROUTING
 | --------------------------------------------------------------------------
 */
Route::view('welcome', 'welcome');
Route::middleware(['localization'])->group(function () {
    Route::controller(LandingPageController::class)->group(function () {
        Route::get('/', 'index')->name('callnchat.index');
        Route::get('about', 'about')->name('about');
        Route::get('contact', 'contact')->name('contact');
        Route::get('product', 'product')->name('product');
        Route::get('faq', [LandingPageController::class, 'faq'])->name('faq');
        Route::get('blog', [LandingPageController::class, 'blog'])->name('blog');

        Route::prefix('product')->group(function () {
            Route::get('self-service-contact-center', 'selfServices')->name('self-service-contact-center');
            Route::get('contact-center-software', 'contactCenter')->name('contact-center-software');
            Route::get('ticketing-management-system', 'TicketingManagement')->name('ticketing-management');
            Route::get('workforce-performance', 'WorkforcePerformance')->name('workforce-performance');
            Route::get('features-and-benefits', 'FeaturesBenefits')->name('features-benefits');
            Route::get('omnichannel-contact-center', 'OmniChannel')->name('omnichannel-contact-center');
            Route::get('automation-customer-service', 'AutomationCustomerService')->name('automation-customer-service');
            Route::get('sdm-outsourcing', 'SdmOutsourcing')->name('sdm-outsourcing');
            Route::get('ai-qa-scoring', 'AiQaScoring')->name('ai-qa-scoring');
        });

        Route::get('faq', 'faq')->name('faq');
        Route::get('terms-condition', 'SyaratKetentuan')->name('syarat-ketentuan');
        Route::get('privacy-policy', 'KebijakanPrivasi')->name('kebijakan-privasi');
    });

    Route::get('lang/{locale}/{slug?}', [LandingPageController::class, 'setLang'])->name('callnchat.lang');
    Route::post('/send-message', [LandingPageController::class, 'sendMessage'])->name('contact.send');
    Route::prefix('blog')->group(function () {
        Route::get('{slug}', [BlogController::class, 'showBlog'])->name('blog.show.id');
    });

    Route::prefix('en')->group(function () {
        Route::get('/', [LandingPageController::class, 'index'])->name('en.index');
        Route::get('about', [LandingPageController::class, 'about'])->name('about.en');
        Route::get('contact', [LandingPageController::class, 'contact'])->name('contact.en');
        Route::get('product', [LandingPageController::class, 'product'])->name('product.en');
        Route::get('faq', [LandingPageController::class, 'faq'])->name('faq.en');

        Route::prefix('product')->group(function () {
            Route::get('self-service-contact-center', [LandingPageController::class, 'selfServices'])->name('self-service-contact-center.en');
            Route::get('contact-center-software', [LandingPageController::class, 'contactCenter'])->name('contact-center-software.en');
            Route::get('ticketing-management-system', [LandingPageController::class, 'TicketingManagement'])->name('ticketing-management.en');
            Route::get('workforce-performance', [LandingPageController::class, 'WorkforcePerformance'])->name('workforce-performance.en');
            Route::get('features-and-benefits', [LandingPageController::class, 'FeaturesBenefits'])->name('features-benefits.en');
            Route::get('omnichannel-contact-center', [LandingPageController::class, 'OmniChannel'])->name('omnichannel-contact-center.en');
            Route::get('automation-customer-service', [LandingPageController::class, 'AutomationCustomerService'])->name('automation-customer-service.en');
            Route::get('sdm-outsourcing', [LandingPageController::class, 'SdmOutsourcing'])->name('sdm-outsourcing.en');
            Route::get('ai-qa-scoring', [LandingPageController::class, 'AiQaScoring'])->name('ai-qa-scoring.en');
        });

        Route::get('blog', [LandingPageController::class, 'blog'])->name('blog.en');
        Route::get('blog/{slug}', [BlogController::class, 'showBlog'])->name('blog.show.en');
        Route::get('terms-condition', [LandingPageController::class, 'SyaratKetentuan'])->name('terms-condition.en');
        Route::get('privacy-policy', [LandingPageController::class, 'KebijakanPrivasi'])->name('kebijakan-privasi.en');
    });
});


/*
 | --------------------------------------------------------------------------
 | CALL & CHAT WEB EMBED ROUTING URL
 | --------------------------------------------------------------------------
 | DON'T CHANGE IF YOU DON'T KNOW
 */
Route::get('/9uf4keeB7kefcn5Joozp/{companyId}', CallnChatConfigController::class)->name('callnchat.config');

Route::get('/{username}', function ($username) {
    return to_route('callnchat.index', [$username, 'contact']);
});
Route::get('{username}/{any}', CallnChatIndexController::class)->where('any', '.*')->name('callnchat.index');
