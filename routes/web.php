<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\RoomController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AuthController;

/*
|--------------------------------------------------------------------------
| ROUTE UTAMA
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| ROUTE LOGIN (GET)
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('welcome');
})->name('login');


/*
|--------------------------------------------------------------------------
| ROUTE LOGIN (POST) — MEMBUAT SESSION LARAVEL
|--------------------------------------------------------------------------
| Route ini WAJIB ada di web.php (bukan api.php) agar session aktif.
| Login via AJAX di welcome.blade.php akan memanggil /api/login.
|--------------------------------------------------------------------------
*/

Route::post('/api/login', [AuthController::class, 'login']);

Route::post('/api/logout', [AuthController::class, 'logout']);


/*
|--------------------------------------------------------------------------
| ROUTE ADMIN
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::get('/rooms', function () {
    return view('rooms');
});

Route::get('/facilities', function () {
    return view('facilities');
});

Route::get('/information', function () {
    return view('information');
});

Route::get('/laundry', function () {
    return view('laundry');
});

Route::get('/chatbot', function () {
    return view('chatbot');
});

Route::get('/questions', function () {
    return view('questions');
});

Route::get('/settings', function () {
    return view('settings');
})->name('settings');


/*
|--------------------------------------------------------------------------
| ROUTE USER (PENGHUNI) — DIPROTEKSI AUTH
|--------------------------------------------------------------------------
| Semua halaman user sekarang butuh login.
| Tanpa login → redirect ke /login (expected behavior).
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        // Dashboard
        Route::view('/dashboard', 'user.dashboard')
            ->name('dashboard');

        // Informasi Kost
        Route::view('/informasi-kost', 'user.inform')
            ->name('inform');

        // Kamar dan Fasilitas — DINAMIS dari database
        Route::get('/rooms', [RoomController::class, 'index'])
            ->name('rooms');

        // Detail kamar — dinamis berdasarkan slug
        // Contoh: /user/rooms/standard, /user/rooms/kamar-melati, dll
        Route::get('/rooms/{slug}', [RoomController::class, 'show'])
            ->name('rooms.show');

        // Chatbot
        Route::view('/chatbot', 'user.chatbot')
            ->name('chatbot');

        /*
        |------------------------------------------------------------------
        | PROFIL USER
        |------------------------------------------------------------------
        | GET /user/profile  → tampilkan halaman profil
        | PATCH /user/profile → simpan perubahan data
        |------------------------------------------------------------------
        */
        Route::get('/profile', [ProfileController::class, 'index'])
            ->name('profile');

        Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');

        // Upload foto profil
        Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])
            ->name('profile.photo');

        // Update password
        Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])
            ->name('profile.password');
    });


/*
|--------------------------------------------------------------------------
| ROUTE LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', function () {

    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/')
        ->with('success', 'Berhasil logout.');

})->middleware('auth')->name('logout');


/*
|--------------------------------------------------------------------------
| ROUTE RESET PASSWORD
|--------------------------------------------------------------------------
*/

Route::get('/reset-password/{token}', function ($token) {

    return view('reset-password', [
        'token' => $token
    ]);

})->name('password.reset');