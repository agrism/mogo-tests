<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Team extends Model
{

    /**
     * @var null
     */
    private $group = null;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function group()
    {
        return $this->belongsToMany(Group::class, 'group_teams');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function homeGames()
    {
        return $this->hasMany(Game::class, 'home_team_id');
    }

    public function guestGames()
    {
        return $this->hasMany(Game::class, 'guest_team_id');
    }

    public function setGroup(Group $group)
    {
        $this->group = $group;
        return $this;
    }


    /**
     * @return static
     */
    public function getGames()
    {
        if ($this->group) {

            $filteredHomeGames = $this->group->games->where('home_team_id', $this->id);

            $filteredGuestGames = $this->group->games->where('guest_team_id', $this->id);

        } else {
            $filteredHomeGames = $this->homeGames()->get();

            $filteredGuestGames = $this->guestGames()->get();
        }

        $mergedFilteredGames = $filteredHomeGames->merge($filteredGuestGames);

        $this->score = 0;

        $mergedFilteredGames->each(function ($game) {
            $this->score += $game->getTeamPoints($this);
        });

        return $mergedFilteredGames->sortBy('id');
    }
}
