<?php

use App\Livewire\Main\CreateMaterialRequest;
use App\Livewire\Main\MaterialRequest;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::name('main.')->group(function () {
        Route::prefix('material-request')->name('material-request.')->group(function () {
            Route::get('/', MaterialRequest::class)->name('index');
            Route::get('/create', CreateMaterialRequest::class)->name('create');
        });
    });
});

require __DIR__.'/auth.php';
