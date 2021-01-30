<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DownloadFileController;

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

Route::get('/logout', 'App\Http\Controllers\Auth\LoginController@logout');
Auth::routes(['verify' => true]);
Route::get('/', function () {
    return view('auth.login');
});
Route::group(['middleware' => 'auth'], function(){
    
    
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    Route::get('/file-download', [DownloadFileController::class, 'index'])->name('download');
    
    //teams
    Route::get('teams', [App\Http\Controllers\TeamsController::class, 'index'])->name('teams');
    Route::get('teams/create', [App\Http\Controllers\TeamsController::class, 'create'])->name('teams_create');
    Route::post('team/store', [App\Http\Controllers\TeamsController::class, 'store'])->name('team_store');
	Route::get('team/{id}', [App\Http\Controllers\TeamsController::class, 'get_data'])->name('team_data');
    Route::delete('team/{id}', [TeamsController::class, 'destroy']);
    Route::delete('team/destroy/{id}', [App\Http\Controllers\TeamsController::class, 'destroy'])->name('teams_destroy');
    Route::post('team/import', [App\Http\Controllers\TeamsController::class, 'import'])->name('teams_import');
    //players
    Route::get('players', [App\Http\Controllers\PlayersController::class, 'index'])->name('players');
    Route::get('players/create', [App\Http\Controllers\PlayersController::class, 'create'])->name('players_create');
    Route::post('player/store', [App\Http\Controllers\PlayersController::class, 'store'])->name('player_store');
	Route::get('player/{id}', [App\Http\Controllers\PlayersController::class, 'get_data'])->name('player_data');
    Route::delete('player/destroy/{id}', [App\Http\Controllers\PlayersController::class, 'destroy'])->name('players_destroy');
    //lineup
    Route::get('lineups', [App\Http\Controllers\LineupsController::class, 'index'])->name('lineups');
    Route::get('lineups/create', [App\Http\Controllers\LineupsController::class, 'create'])->name('lineups_create');
    Route::post('lineup/store', [App\Http\Controllers\LineupsController::class, 'store'])->name('lineup_store');
	Route::get('lineup/{id}', [App\Http\Controllers\LineupsController::class, 'get_data'])->name('lineup_data');
    Route::delete('lineup/destroy/{id}', [App\Http\Controllers\LineupsController::class, 'destroy'])->name('lineups_destroy');
    //matches
    Route::get('matches', [App\Http\Controllers\MatchesController::class, 'index'])->name('matches');
    Route::get('matches/create', [App\Http\Controllers\MatchesController::class, 'create'])->name('matches_create');
    Route::post('matches/store', [App\Http\Controllers\MatchesController::class, 'store'])->name('matches_store');
	Route::get('matches/{id}', [App\Http\Controllers\MatchesController::class, 'get_data'])->name('matches_data');
    Route::delete('matches/destroy/{id}', [App\Http\Controllers\MatchesController::class, 'destroy'])->name('matches_destroy');
});

//Public API
Route::get('get-players','App\Http\Controllers\PlayersController@get_players');
Route::get('get-lineups','App\Http\Controllers\LineupsController@get_lineups');
Route::put('substitute/{id}','App\Http\Controllers\LineupsController@substitute');