<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\TeacherTransferController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;

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
})->name('welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/changepassword', [ProfileController::class, 'passwordreset'])->name('password.change');
Route::post('/changepassword', [ProfileController::class, 'updatePassword'])->name('password.change.submit');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/myprofile', [UserController::class, 'myprofile'])->name('profile.myprofile');
    Route::get('/myprofileedit', [UserController::class, 'myprofileedit'])->name('profile.myprofileedit');
    Route::get('/myappointment', [UserController::class, 'myappointment'])->name('profile.myappointment');

Route::get('/teacher/dashboard', [UserController::class, 'teacherindex'])->name('teacher.dashboard');
Route::get('/teacher/register', [UserController::class, 'teachercreate'])->name('teacher.register');
Route::post('/teacher/register', [UserController::class, 'teacherstore'])->name('teacher.store');
Route::get('/teacher/search', [UserController::class, 'teachersearch'])->name('teacher.search');
Route::get('/teacher/reports', [UserController::class, 'teacherreports'])->name('teacher.reports');
Route::get('/teacher/fullreportcurrent', [UserController::class, 'teacherfullreportcurrent'])->name('teacher.fullreportcurrent');
Route::get('/teacher/profile', [UserController::class, 'teacherprofile'])->name('teacher.profile');
Route::get('/teacher/profileedit', [UserController::class, 'teacherprofileedit'])->name('teacher.profileedit');
Route::get('/teacher/transfer', [TeacherTransferController::class, 'teacherindex'])->name('teacher.transfer');
Route::post('/teacher/transfercompleate', [TeacherTransferController::class, 'teacherstore'])->name('teacher.transferstore');
Route::get('/teacher-transfers-pdf', [TeacherTransferController::class, 'teacherPersonalPdf']);



Route::get('/principal/dashboard', [UserController::class, 'principalindex'])->name('principal.dashboard');
Route::get('/principal/register', [UserController::class, 'principalcreate'])->name('principal.register');
Route::post('/principal/register', [UserController::class, 'principalstore'])->name('principal.store');
Route::get('/principal/search', [UserController::class, 'principalsearch'])->name('principal.search');
Route::get('/principal/reports', [UserController::class, 'principalreports'])->name('principal.reports');
Route::get('/principal/fullreportcurrent', [UserController::class, 'principalfullreportcurrent'])->name('principal.fullreportcurrent');
Route::get('/principal/profile', [UserController::class, 'principalprofile'])->name('principal.profile');
Route::get('/principal/transfer', [TransferController::class, 'principalindex'])->name('principal.transfer');

Route::get('/sleas/dashboard', [UserController::class, 'sleasindex'])->name('sleas.dashboard');
Route::get('/sleas/register', [UserController::class, 'sleascreate'])->name('sleas.register');
Route::post('/sleas/register', [UserController::class, 'sleasstore'])->name('sleas.store');
Route::get('/sleas/search', [UserController::class, 'sleassearch'])->name('sleas.search');
Route::get('/sleas/reports', [UserController::class, 'sleasreports'])->name('sleas.reports');
Route::get('/sleas/fullreportcurrent', [UserController::class, 'sleasfullreportcurrent'])->name('sleas.fullreportcurrent');
Route::get('/sleas/profile', [UserController::class, 'sleasprofile'])->name('sleas.profile');

Route::get('/student/dashboard', [StudentController::class, 'index'])->name('student.dashboard');
Route::get('/student/register', [StudentController::class, 'create'])->name('student.register');
Route::post('/student/register', [StudentController::class, 'store'])->name('student.store');
Route::get('/student/search', [StudentController::class, 'search'])->name('student.search');
Route::get('/student/reports', [StudentController::class, 'reports'])->name('student.reports');
Route::get('/student/profile', [StudentController::class, 'profile'])->name('student.profile');


Route::get('/school/search', [SchoolController::class, 'search'])->name('school.search');
Route::get('/school/profile/{id?}', [SchoolController::class, 'profile'])->name('school.profile');
Route::get('/school/classprofile/{id?}', [SchoolController::class, 'classprofile'])->name('school.classprofile');
Route::get('/school/classsetup', [SchoolController::class, 'classsetup'])->name('school.classsetup');
Route::post('/school/classprofile/{id?}', [SchoolController::class, 'classstore'])->name('school.classstore');

Route::get('/school/dashboard', [SchoolController::class, 'index'])->name('school.dashboard');
Route::get('/school/reports', [SchoolController::class, 'reports'])->name('school.reports');
Route::get('/school/classdashboard', [SchoolController::class, 'classindex'])->name('school.classdashboard');
});



require __DIR__.'/auth.php';
