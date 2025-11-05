<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\SuperAdminController;

Route::group(['middleware' => 'guest'], function () {
    Route::get('', [LoginController::class, 'index'])->name('login');
    Route::post('do_login', [LoginController::class, 'doLogin'])->name('do_login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');

    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
        Route::get('superadmin', [SuperAdminController::class, 'index'])->name('superadmin');
    });
});
