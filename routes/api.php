<?php

use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::get('/posts', function () {
    return Post::query()
        ->orderByDesc('published_at')
        ->get(['id', 'title', 'mood', 'body', 'published_at']);
});
