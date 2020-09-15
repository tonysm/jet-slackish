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

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia\Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/home', function () {
        $team = request()->user()->currentTeam;
        $channel = $team->channels()->first();

        return redirect()->route('channels.show', $channel);
    })->name('home');

    Route::post('teams/{team}/channels', function (\App\Models\Team $team) {
        $channel = (new \App\Actions\Channels\CreateNewChannel())->handle(
            request()->user(),
            $team,
            request()->only('name')
        );

        return redirect()->route('channels.show', $channel);
    })->name('teams.channels.store');

    Route::get('channels/{channel}', function (\App\Models\Channel $channel) {
        $channels = request()->user()->currentTeam->channels;

        return \Inertia\Inertia::render('Channels/Show', [
            'channels' => $channels,
            'currentChannel' => $channel,
            'messages' => $channel->messages()
                ->latest()
                ->take(50)
                ->with(['user', 'content'])
                ->get()
                ->reverse()
                ->values(),
        ]);
    })->name('channels.show');

    Route::post('/channels/{channel}/messages', function (\App\Models\Channel  $channel) {
        request()->validate(['content' => 'required|string']);

        (new App\Actions\Channels\CreateNewMessage())->handle(
            request()->user(),
            $channel,
            request()->input('content')
        );

        return redirect()->back();
    })->name('channels.messages.store');

    Route::put('/switch-teams/{team}', function (\App\Models\Team $team) {
        (new App\Actions\Teams\SwitchTeams())->handle(request()->user(), $team);

        return redirect()->route('channels.show', $team->channels()->first());
    })->name('teams.switch');
});
