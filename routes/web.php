<?php

use App\Http\Controllers\Auth\TeacherAuthController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\ChatController;

Route::get('/', function(){
    return view('welcome'); // or homepage with login/signup choices
})->name('home');

Route::post('/send-message', [ChatController::class, 'sendMessage'])->middleware('auth:teacher,student');


// Teacher routes
Route::prefix('teacher')->group(function(){
    Route::get('register', [TeacherAuthController::class, 'showRegisterForm'])->name('teacher.register');
    Route::post('register', [TeacherAuthController::class, 'register']);
    Route::get('login', [TeacherAuthController::class, 'showLoginForm'])->name('teacher.login');
    Route::post('login', [TeacherAuthController::class, 'login']);
    Route::post('logout', [TeacherAuthController::class, 'logout'])->name('teacher.logout');
    Route::get('chat', [ChatController::class, 'teacherChat'])->middleware('auth:teacher')->name('teacher.chat');
});

// Student routes
Route::prefix('student')->group(function(){
    Route::get('register', [StudentAuthController::class, 'showRegisterForm'])->name('student.register');
    Route::post('register', [StudentAuthController::class, 'register']);
    Route::get('login', [StudentAuthController::class, 'showLoginForm'])->name('student.login');
    Route::post('login', [StudentAuthController::class, 'login']);
    Route::post('logout', [StudentAuthController::class, 'logout'])->name('student.logout');
    Route::get('chat', [ChatController::class, 'studentChat'])->middleware('auth:student')->name('student.chat');
});

