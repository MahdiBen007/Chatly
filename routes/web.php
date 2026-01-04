<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatlyController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/Chatly', [ChatlyController::class, 'index'])->name('Chatly');
    Route::get('/chat/{user}', [ChatlyController::class, 'chat'])->name('chat.show');
    Route::post('/message/{user}', [ChatlyController::class, 'store'])->name('message.store');
    Route::get('/room/{roomKey}', [ChatlyController::class, 'room'])->name('room.show');
    Route::post('/room/{roomKey}/message', [ChatlyController::class, 'storeRoom'])->name('room.message.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/sql-test', function (Request $request) {
    $email = $request->query('email');
    $safe = DB::table('users')->where('email', $email)->first();
    $unsafe = null;

    try {
        $unsafe = DB::select("SELECT * FROM users WHERE email = ?", [$email]);
    } catch (\Exception $e) {
        $unsafe = $e->getMessage();
    }

    return response()->json([
        'email' => $email,
        'safe_result' => $safe,
        'unsafe_result' => $unsafe,
    ]);
});

Route::view('/terms', 'terms')->name('terms.show');
Route::view('/policy', 'policy')->name('policy.show');

require __DIR__ . '/auth.php';
