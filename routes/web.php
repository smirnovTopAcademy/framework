<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Cookie;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

    // $count = session('main_count', 0);
    // $count++;
    // session(['main_count' => $count]);


    // echo session('main_count');

    echo "Last visit " . Cookie::get('last_visit', 'Never was');

    Cookie::make('last_visit', date('Y-m-d H:i:s'), 60 * 24 * 30);

    return view('welcome');
})->name('main');

Route::get('/registration', [App\Http\Controllers\SignUp::class, 'form'])
  ->name('registration.form');

Route::post('/registration', [App\Http\Controllers\SignUp::class, 'post'])
    ->name('registration.post');

Route::get('/login', [App\Http\Controllers\SignIn::class, 'form'])
  ->name('sign_in.form');

Route::post('/login', [App\Http\Controllers\SignIn::class, 'post'])
    ->name('sign_in.post');

Route::get('/logout', [App\Http\Controllers\SignIn::class, 'logout'])
    ->name('sign_in.logout');





Route::get('/landing', [App\Http\Controllers\Landing::class, 'index']);

Route::get('/age', [App\Http\Controllers\Landing::class, 'json']);

Route::get('/questions', [App\Http\Controllers\Questions::class, 'get'])
  ->name('question.get');

Route::post('/questions', [App\Http\Controllers\Questions::class, 'post'])
  ->name('question.post');

Route::get('/questions/list', [App\Http\Controllers\Questions::class, 'listAll'])
  ->name('question.list');
