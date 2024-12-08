<?php

use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('posts', [PostController::class, 'index']);
// Route::get('/posts', function (Request $request) {
//     return $request->user();
// });

Route::resource('/posts', PostController::class);