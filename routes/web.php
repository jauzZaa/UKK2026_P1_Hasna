<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LokasiController;
use App\Http\Controllers\ToolUnitController;
use App\Http\Controllers\PeminjamanController;

Route::get('/', function () {
    return view('auth.login');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', function () {
    $role = strtolower(auth()->user()->role);

    if ($role == 'admin') {
        return view('dashboard');
    } elseif ($role == 'employee') {
        return view('dashboard');
    } elseif ($role == 'user') {
        return redirect()->route('alat.tampil');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::match(['put', 'patch'], '/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/user', [UserController::class, 'tampil'])->name('user.tampil');
Route::get('/user/tambah', [UserController::class, 'tambah'])->name('user.tambah');
Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
Route::put('/user/update/{id}', [UserController::class, 'update'])->name('user.update');
Route::delete('/user/delete/{id}', [UserController::class, 'destroy'])->name('user.destroy');

Route::get('/alat', [AlatController::class, 'tampil'])->name('alat.tampil');
Route::get('/alat/tambah', [AlatController::class, 'tambah'])->name('alat.tambah');
Route::post('/alat/store', [AlatController::class, 'store'])->name('alat.store');
Route::get('/alat/edit/{id}', [AlatController::class, 'edit'])->name('alat.edit');
Route::put('/alat/update/{id}', [AlatController::class, 'update'])->name('alat.update');
Route::delete('/alat/delete/{id}', [AlatController::class, 'destroy'])->name('alat.destroy');

Route::get('/category', [CategoryController::class, 'tampil'])->name('category.tampil');
Route::get('/category/tambah', [CategoryController::class, 'tambah'])->name('category.tambah');
Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
Route::get('/category/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
Route::put('/category/update/{id}', [CategoryController::class, 'update'])->name('category.update');
Route::delete('/category/destroy/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

Route::get('/lokasi', [LokasiController::class, 'tampil'])->name('lokasi.tampil');
Route::get('/lokasi/tambah', [LokasiController::class, 'tambah'])->name('lokasi.tambah');
Route::post('/lokasi/store', [LokasiController::class, 'store'])->name('lokasi.store');
Route::get('/lokasi/edit/{location_code}', [LokasiController::class, 'edit'])->name('lokasi.edit');
Route::put('/lokasi/update/{location_code}', [LokasiController::class, 'update'])->name('lokasi.update');
Route::delete('/lokasi/destroy/{location_code}', [LokasiController::class, 'destroy'])->name('lokasi.destroy');
Route::get('/alat/detail/{id}', [AlatController::class, 'detail'])->name('alat.detail');

Route::post('/unit/store', [ToolUnitController::class, 'store'])->name('unit.store');
Route::put('/unit/update/{code}', [ToolUnitController::class, 'update'])->name('unit.update');
Route::delete('/unit/destroy/{code}', [ToolUnitController::class, 'destroy'])->name('unit.destroy');

Route::get('/peminjaman', [PeminjamanController::class, 'tampil'])->name('peminjaman.tampil');
Route::get('/peminjaman/tambah', [PeminjamanController::class, 'tambah'])->name('peminjaman.tambah');
Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
Route::get('/peminjaman/units/{toolId}', [PeminjamanController::class, 'getUnits'])->name('peminjaman.units');
Route::patch('/peminjaman/{peminjaman}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
Route::patch('/peminjaman/{peminjaman}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
Route::get('/riwayat', [PeminjamanController::class, 'riwayat'])->name('peminjaman.riwayat');

require __DIR__ . '/auth.php';
