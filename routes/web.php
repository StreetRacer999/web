<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

//Route::post('/machines', 'App\Http\Controllers\MachinesController@create');

Route::resource('machines', 'App\Http\Controllers\MachinesController')->names([
    'machines' => 'machines.index'
    ]);
Route::get('/machines', 'App\Http\Controllers\MachinesController@index')->name('machines');