<?php

namespace App\Models\Scheme;

use App\Models\Group;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Collection;

class Playoff
{
    /**
     * @var Tournament
     */
    private $tournament;

    /**
     * @var int
     */
    private $teamsCountGoesOutFromGroup = 1;

    /**
     * Playoff constructor.
     * @param Tournament $tournament
     */
    public function __construct(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    /**
     * @param int $teamsCount
     * @return Playoff
     */
    public function setTeamsCountGoesOutFromGroup(int $teamsCount): self
    {
        $this->teamsCountGoesOutFromGroup = $teamsCount;
        return $this;
    }

    public function create()
    {
        $this->groupLetters = GroupLetters::get();

        $this->tournament->groups->each(function () {
            array_shift($this->groupLetters);
        });

        $this->level = $this->tournament->groups->max('pivot.level');

        if ($this->level > 1) {
            $this->teamsCountGoesOutFromGroup = 1;
        }

        $groups = $this->tournament->groups->filter(function ($group) {
            return $group->pivot->level == $this->level;
        });


        $this->rankedGroups = collect();

        $groups->each(function ($group, $index) {

            $rankedTeams = $group->getRankedTeams()->take($this->teamsCountGoesOutFromGroup);

            if ($index % 2) {
                $rankedTeams = $rankedTeams->reverse();
            }
            $this->rankedGroups->push($rankedTeams);
        });


        $this->pairs = collect();

        $this->pairBuffer = collect();

        collect(range(1, $this->rankedGroups->first()->count()))->each(function ($val, $index) {
            $this->rankedGroups->each(function ($group, $i) use ($index) {

                $team = $group->slice($index, 1)->first();

                if ($i % 2) {
                    $this->pairBuffer->push($team);
                    $this->pairs->push($this->pairBuffer);
                    $this->pairBuffer = collect();
                } else {
                    $this->pairBuffer->push($team);
                }
            });
        });


        $this->level++;

        $this->pairs->each(function ($teamPair) {

            $group = new Group();

            $group->name = array_shift($this->groupLetters);

            $group->save();

            $this->teamsCollection = new Collection();

            $teamPair->each(function ($team) {
                $this->teamsCollection->push($team);
            });

            $group->teams()->attach($this->teamsCollection);

            $this->tournament->groups()->attach($group, ['level' => $this->level]);
        });
    }


}
