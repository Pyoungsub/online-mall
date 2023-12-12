<?php

use Illuminate\Support\Facades\Route;
use App\Livewire;
use App\Http\Controllers\OauthController;
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


Route::prefix('google')->group(function () {
    Route::get('/login', function (){
        return Socialite::driver('google')->redirect();
    })->name('google.login');
    Route::get('/oauth', [OauthController::class, 'google']);
});
Route::prefix('facebook')->group(function () {
    Route::get('/login', function (){
        return Socialite::driver('facebook')->redirect();
    })->name('facebook.login');
    Route::get('/oauth', [OauthController::class, 'facebook']);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::prefix('business')->group(function () {
        Route::get('/dashboard', Livewire\Business\Dashboard\Index::class)->name('business.dashboard');
        Route::get('/signup/search', Livewire\Business\Signup\SearchBrn::class)->name('business.signup.search');
        Route::get('/signup/submit-document', Livewire\Business\Signup\SubmitDocument::class)->name('business.signup.submit-document');
    });
    Route::get('/direct', Livewire\Direct\Index::class)->name('direct');
    Route::get('/', Livewire\Main\Index::class)->name('home');
});
