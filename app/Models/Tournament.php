<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tournament extends Model
{

    /**
     * @return BelongsToMany
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'tournament_groups', 'tournament_id', 'group_id')->withPivot('level');;
    }


    /**
     * @return BelongsToMany
     */
    public function scopeLatests(): Builder
    {
        return $this->orderBy('id', 'desc');
    }

    public function generateGames(){
        $this->groups->each(function ($group) {
            $group->teams->each(function ($team) use ($group) {


                $group->teams->each(function ($secondTeam) use ($team, $group) {

                    if ($team->id !== $secondTeam->id) {

                        $game = Game::where(function ($game) use ($team, $secondTeam) {
                            $game->where('guest_team_id', $team->id)->where('home_team_id', $secondTeam->id);
                        })->orWhere(function ($game) use ($team, $secondTeam) {
                            $game->where('home_team_id', $team->id)->where('guest_team_id', $secondTeam->id);
                        })
                            ->whereHas('groups', function ($gr) use ($group) {
                                $gr->where('groups.id', $group->id);
                            })
                            ->first();

                        if (!$game) {

                            $game = new Game();
                            $game->home_team_id = $team->id;
                            $game->guest_team_id = $secondTeam->id;
                            $game->save();

                            $group->games()->attach($game);
                            $group->games->push($game);
                        }
                    }

                });
            });
        });
    }
}
