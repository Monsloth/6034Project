<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TravelIdeaController;

Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/welcome', function () {
    return view('welcome');
});
// 在 Laravel 8 及以后的版本中，路由定义已经改变，
// 不再使用字符串来指定控制器和方法，而是使用一个数组来引用控制器类和方法。这种改变提供了更好的命名空间支持
Route::get('/travel_ideas', [TravelIdeaController::class, 'index'])->name('travel_ideas.index');
Route::get('/travel_ideas/create', [TravelIdeaController::class, 'create'])->name('travel_ideas.create');
Route::post('/travel_ideas', [TravelIdeaController::class, 'store'])->name('travel_ideas.store');

// 登录验证
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 登录界面
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 编辑ideas
Route::get('/travel_ideas/my_ideas', [TravelIdeaController::class, 'showUserIdeas'])->name('travel_ideas.my_ideas');

Route::get('/travel_ideas/{idea}/edit', [TravelIdeaController::class, 'edit'])->name('travel_ideas.edit');
Route::put('/travel_ideas/{idea}', [TravelIdeaController::class, 'update'])->name('travel_ideas.update');


require __DIR__ . '/auth.php';
