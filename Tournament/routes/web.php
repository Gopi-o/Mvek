<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

use App\Http\Controllers\TournamentController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\RoundController;
use App\Http\Controllers\TournamentMatchController;

use App\Models\Tournament;
use App\Models\Participant;

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

    Participant::firstOrCreate(
        ['user_id' => $user->id, 'type' => 'individual'],
        ['name' => $user->name]
    );

    Auth::login($user);
    return redirect('/');
});

Route::get('/', function () {
    $latestTournaments = Tournament::with(['type', 'owner'])
        ->orderBy('created_at', 'desc')
        ->take(6)
        ->get();

    return view('home', compact('latestTournaments'));
})->name('home');

Route::view('/faq', 'pages.faq')->name('faq');

Route::middleware('auth')->get('/profile', function () {
    return view('profile.show', ['user' => Auth::user()]);
})->name('profile.show');

Route::middleware('auth')->get('/admin', function () {
    abort_unless(Auth::user()?->role === 'admin', 403);

    $stats = [
        'users' => User::count(),
        'tournaments' => Tournament::count(),
        'participants' => Participant::count(),
        'active_tournaments' => Tournament::where('status', 'active')->count(),
    ];

    $recentUsers = User::orderByDesc('id')->take(10)->get();
    $recentTournaments = Tournament::with('owner')->orderByDesc('id')->take(10)->get();

    return view('admin.dashboard', compact('stats', 'recentUsers', 'recentTournaments'));
})->name('admin.dashboard');


Route::resource('tournaments', TournamentController::class);
Route::get('tournaments/{tournament}/generate', [TournamentController::class, 'generateBracket'])->name('tournaments.generate');
Route::post('tournaments/{tournament}/start', [TournamentController::class, 'start'])->name('tournaments.start');
Route::post('tournaments/{tournament}/managers', [TournamentController::class, 'addManager'])->name('tournaments.managers.store');
Route::delete('tournaments/{tournament}/managers/{user}', [TournamentController::class, 'removeManager'])->name('tournaments.managers.destroy');


Route::resource('participants', ParticipantController::class)
    ->only(['index', 'store', 'update', 'destroy'])
    ->middleware('auth');
Route::post('participants/register/{tournament}', [ParticipantController::class, 'registerToTournament'])
    ->middleware('auth')
    ->name('participants.register');

Route::middleware('auth')->group(function () {
    Route::post('teams', [TeamController::class, 'store'])->name('teams.store');
    Route::post('teams/{team}/add-member', [TeamController::class, 'addMember'])->name('teams.addMember');
    Route::post('teams/{team}/remove-member', [TeamController::class, 'removeMember'])->name('teams.removeMember');
    Route::get('teams/{team}', [TeamController::class, 'show'])->name('teams.show');
});


Route::get('tournaments/{tournament}/rounds', [RoundController::class, 'index'])->name('tournaments.rounds');
Route::get('rounds/{round}', [RoundController::class, 'show'])->name('rounds.show');

Route::get('tournaments/{tournament}/matches', [TournamentMatchController::class, 'index'])->name('tournaments.matches');
Route::get('matches/{match}', [TournamentMatchController::class, 'show'])->name('matches.show');
Route::post('matches/{match}/result', [TournamentMatchController::class, 'updateResult'])->name('matches.result');
Route::post('matches/{match}/schedule', [TournamentMatchController::class, 'schedule'])->name('matches.schedule');

