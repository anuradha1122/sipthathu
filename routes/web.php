<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/teacher/dashboard', [UserController::class, 'teacherindex'])->name('teacher.dashboard');
Route::get('/teacher/register', [UserController::class, 'teachercreate'])->name('teacher.register');
Route::post('/teacher/register', [UserController::class, 'teacherstore'])->name('teacher.store');
Route::get('/teacher/search', [UserController::class, 'teachersearch'])->name('teacher.search');
Route::get('/teacher/reports', [UserController::class, 'teacherreports'])->name('teacher.reports');
Route::get('/teacher/profile', [UserController::class, 'teacherprofile'])->name('teacher.profile');

Route::get('/principal/dashboard', [UserController::class, 'principalindex'])->name('principal.dashboard');
Route::get('/principal/register', [UserController::class, 'principalcreate'])->name('principal.register');
Route::post('/principal/register', [UserController::class, 'principalstore'])->name('principal.store');
Route::get('/principal/search', [UserController::class, 'principalsearch'])->name('principal.search');
Route::get('/principal/reports', [UserController::class, 'principalreports'])->name('principal.reports');
Route::get('/principal/profile', [UserController::class, 'principalprofile'])->name('principal.profile');

Route::get('/sleas/dashboard', [UserController::class, 'sleasindex'])->name('sleas.dashboard');
Route::get('/sleas/register', [UserController::class, 'sleascreate'])->name('sleas.register');
Route::post('/sleas/register', [UserController::class, 'sleasstore'])->name('sleas.store');
Route::get('/sleas/search', [UserController::class, 'sleassearch'])->name('sleas.search');
Route::get('/sleas/reports', [UserController::class, 'sleasreports'])->name('sleas.reports');
Route::get('/sleas/profile', [UserController::class, 'sleasprofile'])->name('sleas.profile');

require __DIR__.'/auth.php';
