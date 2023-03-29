<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::middleware([
    // 'auth:api'
])
    ->name('posts.')
    ->namespace("\App\Http\Controllers")
    ->group(function () { 
        Route::get('/posts', [PostController::class, 'index'])->name('index');

        Route::get('/posts/{post}', [PostController::class, 'show'])
            ->name('show')
            ->where(['post' => '[0-9]+']);

        Route::post('/posts', [PostController::class, 'store'])->name('store');
        
        Route::patch('/posts/{post}', [PostController::class, 'update'])->name('update');

        Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('destroy');

});