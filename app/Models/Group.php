<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Group extends Model
{

    /**
     * @return BelongsToMany
     */
    public function tournament(): BelongsToMany
    {
        return $this->belongsToMany(Tournament::class, 'tournament_groups', 'group_id', 'tournament_id')->withPivot('level');
    }

    /**
     * @return BelongsToMany
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'group_teams');
    }

    /**
     * @return BelongsToMany
     */
    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class, 'group_games', 'group_id', 'game_id');
    }

    /**
     * @return Collection
     */
    public function getRankedTeams(): Collection
    {
        $this->teamCollection = collect();

        $this->teams->each(function ($team) {

            $this->team = $team;
            $this->team->points = 0;
            $this->team->pointsForSorting = 0;

            $team->setGroup($this)->getGames()->each(function ($game) {

                $gamePoints = $game->getTeamPoints($this->team);

                $this->team->points += $gamePoints;

                $this->team->pointsForSorting += ($gamePoints + ($this->team->id / 10000));
            });

            $this->teamCollection->push($this->team);
        });

        return $this->teamCollection->sortByDesc('pointsForSorting');

    }


}
