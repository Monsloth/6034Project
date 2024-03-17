<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TravelIdeaController;

Route::get('/', function () {
    return view('index');
});
// 在 Laravel 8 及以后的版本中，路由定义已经改变，
// 不再使用字符串来指定控制器和方法，而是使用一个数组来引用控制器类和方法。这种改变提供了更好的命名空间支持
Route::get('/travel_ideas', [TravelIdeaController::class, 'index'])->name('travel_ideas.index');
Route::get('/travel_ideas/create', [TravelIdeaController::class, 'create'])->name('travel_ideas.create');
Route::post('/travel_ideas', [TravelIdeaController::class, 'store'])->name('travel_ideas.store');



// Route::get('/', function () {
//     return view('welcome');
// });
