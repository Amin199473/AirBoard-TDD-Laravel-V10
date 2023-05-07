<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectInvitationsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ProjectTasksController;

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

    Route::resource('/projects',ProjectsController::class);

    Route::post('/projects/{project}/tasks',[ProjectTasksController::class,'store'])->name('projects.tasks');
    Route::patch('/projects/{project}/tasks/{task}',[ProjectTasksController::class,'Update'])->name('projects.tasks');

    Route::post('/projects/{project}/invitations',[ProjectInvitationsController::class,'store'])->name('project.invitations');
});

require __DIR__ . '/auth.php';
