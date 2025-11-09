<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard\InventoriController;
use App\Http\Controllers\Dashboard\KasirController;
use App\Http\Controllers\Dashboard\SuperAdminController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\Manajemen\MenuController;

Route::group(['middleware' => 'guest'], function () {
    Route::get('', [LoginController::class, 'index'])->name('login');
    Route::post('do_login', [LoginController::class, 'doLogin'])->name('do_login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('get_parent_menu', [HelperController::class, 'getParentMenu'])->name('get_parent_menu');

    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
        Route::get('superadmin', [SuperAdminController::class, 'index'])->name('superadmin');
        Route::get('inventori', [InventoriController::class, 'index'])->name('inventori');
        Route::get('kasir', [KasirController::class, 'index'])->name('kasir');
    });

    Route::group(['prefix' => 'manajemen', 'as' => 'manajemen.'], function () {
        Route::resource('menu', MenuController::class);
    });
});
