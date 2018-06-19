<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Game extends Model
{
    /**
     * @return BelongsToMany
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_games', 'game_id', 'group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guestTeam()
    {
        return $this->belongsTo(Team::class, 'guest_team_id');
    }

    /**
     * @param Team $team
     * @return int
     */
    public function getTeamPoints(Team $team): int
    {
        if ($this->home_team_id == $team->id) {
            return $this->home_team_score ?: 0;
        } else {
            return $this->guest_team_score ?: 0;
        }
    }

    /**
     * @return Game
     */
    public function generateResult(): self
    {
        $this->home_team_point = RAND(0, 1);
        $this->guest_team_point = $this->home_team_point ? 0 : 1;
        $this->home_team_score = $this->home_team_point ? 1 : 0;
        $this->guest_team_score = $this->guest_team_point ? 1 : 0;
        return $this;
    }

    /**
     * @return bool
     */
    public function isGamePlayed(): bool
    {
        return $this->home_team_point ? true : false;
    }
}
