<?php

use App\Domain\Note\NoteController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/notes', NoteController::class)->middleware('auth:sanctum');
Route::post('/notes/file', [NoteController::class, 'file'])->name('notes.file')->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum');
