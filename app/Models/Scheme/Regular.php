<?php

namespace App\Models\Scheme;

use App\Models\Group;
use App\Models\Team;
use App\Models\Tournament;

class Regular
{
    /**
     * @var Tournament
     */
    private $tournament;

    /**
     * @var int
     */
    private $groupQuantity = 2;

    /**
     * @var int
     */
    private $teamQuantityPerGroup = 8;

    /**
     * Regular constructor.
     * @param Tournament $tournament
     */
    public function __construct(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    /**
     * @param int $quantiy
     * @return Regular
     */
    public function setGroupsQuantity(int $quantiy): self
    {
        $this->groupQuantity = $quantiy;
        return $this;
    }

    /**
     * @param int $quantiy
     * @return Regular
     */
    public function setTeamsQuntityPergroup(int $quantiy): self
    {
        $this->teamQuantityPerGroup = $quantiy;
        return $this;
    }

    /**
     * @return Regular
     */
    public function create() : self
    {
        $this->teams = Team::inRandomOrder()->get();

        $this->groupLetters = GroupLetters::get();


        collect(range(1, $this->groupQuantity))->each(function ($val, $index) {
            $group = new Group();
            $group->name = array_shift($this->groupLetters);
            $group->save();

            $teamsCollection = $this->teams
                ->slice($this->teamQuantityPerGroup * $index)
                ->take($this->teamQuantityPerGroup);

            $group->teams()->attach($teamsCollection);
            $this->tournament->groups()->attach($group, ['level' => 1]);
        });

        return $this;
    }
}
