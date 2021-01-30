<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Teams;
use App\Models\Players;
use App\Models\Lineups;
use App\Models\Matches;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {        
        $this->teams = Teams::get()->count();
        $this->players = Players::get()->count();
        $this->lineups = Lineups::get()->count();
        $this->matches = Matches::get()->count();
        view()->share([
            'teams_count' => Teams::get()->count(),
            'players_count' => Players::get()->count(),
            'lineups_count' => Lineups::get()->count(),
            'matches_count' => Matches::get()->count()
        ]);
    }
}
