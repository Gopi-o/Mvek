<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TournamentController;

use App\Models\Tournament;

Route::get('login', function () {
    return view('auth.login');
})->name('login');

Route::post('login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/');
    }

    return back()->withErrors([
        'email' => 'Неверный email или пароль',
    ]);
});

Route::post('logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('register', function () {
    return view('auth.register');
})->name('register');

Route::post('register', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => 'user',
    ]);

    Auth::login($user);
    return redirect('/');
});

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    $latestTournaments = Tournament::with(['type', 'owner'])
        ->orderBy('created_at', 'desc')
        ->take(6)
        ->get();
    
    return view('home', compact('latestTournaments'));
})->name('home');
Route::resource('tournaments', TournamentController::class);

Route::post('tournaments/{tournament}/generate', [TournamentController::class, 'generateBracket'])->name('tournaments.generate');
Route::post('tournaments/{tournament}/start', [TournamentController::class, 'start'])->name('tournaments.start');

Route::resource('participants', ParticipantController::class);
Route::post('participants/register/{tournament}', [ParticipantController::class, 'registerToTournament'])->name('participants.register');


Route::get('tournaments/{tournament}/rounds', [RoundController::class, 'index'])->name('rounds.index');
Route::get('rounds/{round}', [RoundController::class, 'show'])->name('rounds.show');


Route::get('matches/{match}', [TournamentMatchController::class, 'show'])->name('matches.show');
Route::post('matches/{match}/result', [TournamentMatchController::class, 'updateResult'])->name('matches.result');
Route::post('matches/{match}/schedule', [TournamentMatchController::class, 'schedule'])->name('matches.schedule');

