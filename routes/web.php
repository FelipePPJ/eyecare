<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ExamsController;
use App\Http\Controllers\PackagesController;
use App\Http\Controllers\RequestController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/exams', [ExamsController::class, 'index'])->name('exams');
Route::get('/packages', [PackagesController::class, 'index'])->name('packages');
Route::get('/requests', [RequestController::class, 'index'])->name('requests');
