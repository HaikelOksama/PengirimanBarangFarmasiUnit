<?php

use App\Livewire\Main\CreateMaterialRequest;
use App\Livewire\Main\Farmalkes;
use App\Livewire\Main\KirimMatreq;
use App\Livewire\Main\MaterialRequest;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Utils\PrintInvoice;
use App\Livewire\Utils\PrintRequest;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(route('login'));
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
            Route::get('/{matreq}/print', PrintRequest::class)->name('print');
        });
        Route::prefix('permintaan-unit')->name('kirim.')->group(function () {
            Route::get('/', KirimMatreq::class)->name('index');
            Route::get('/{matreq}/print', PrintInvoice::class)->name('print');
            // Route::get('/create', CreateMaterialRequest::class)->name('create');
        });

        Route::middleware('role:admin')->prefix('farmalkes')->name('farmalkes.')->group(function() {
            Route::get('/', Farmalkes::class)->name('index');
        });

    });
});

require __DIR__.'/auth.php';
