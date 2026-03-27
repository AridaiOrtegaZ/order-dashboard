<?php

use App\Http\Controllers\Auth\GithubController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));
Route::get('/login', fn() => redirect()->route('github.redirect'))->name('login');

// Auth
Route::get('/auth/github', [GithubController::class, 'redirect'])->name('github.redirect');
Route::get('/auth/github/callback', [GithubController::class, 'callback']);
Route::post('/logout', [GithubController::class, 'logout'])->name('logout');


// Dashboard protegido
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
});