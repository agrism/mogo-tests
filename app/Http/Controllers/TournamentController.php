<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Scheme\Playoff;
use App\Models\Scheme\Regular;
use App\Models\Tournament;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class TournamentController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(): View
    {
        $tournaments = Tournament::latests()->get();

        return view('tournaments.index', compact('tournaments'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id): View
    {
        $tournament = Tournament::with(['groups' => function ($group) {
            $group
                ->with('teams')
                ->with(['games' => function ($game) {
                    $game->with('homeTeam', 'guestTeam');
                }]);
        }])->find($id);

        if (!$tournament) {
            return $this->index();
        }

        $tournament->groups->each(function ($group) {

            $this->counter = 0;

            $group->teams->each(function ($team, $index) use ($group) {

                $team->games = $team->setGroup($group)->getGames();

                // add Xes for group table

                if ($index == $this->counter) {

                    $g = new Game();
                    $g->id = '0';
                    $g->home_team_id = $team->id;

                    $index = $team->games->search(function ($item, $key) {
                        return $key == $this->counter;
                    });

                    if ($index !== false) {
                        $team->games->splice($index, 0, [$g]);
                    } else {
                        $team->games->push($g);
                    }
                }

                $this->counter++;
            });
        });

        $this->lastLevel = $tournament->groups->max('pivot.level');

        $showGeneratePlayoffNextButton = $tournament->groups->filter(function ($group) {
            return $group->pivot->level == $this->lastLevel;
        })->count() > 1 ? true : false;

        return view('tournaments.show', compact('tournament', 'showGeneratePlayoffNextButton'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(): RedirectResponse
    {
        $tournament = new Tournament();

        $tournament->name = (\Faker\Factory::create())->country . ' Cup';

        $tournament->save();

        (new Regular($tournament))->setGroupsQuantity(2)->setTeamsQuntityPergroup(8)->create();

        $tournament->generateGames();

        return redirect()->route('tournaments.show', $tournament);
    }


    /**
     * @param $id
     * @return RedirectResponse
     */
    public function generatePlayoff($id): RedirectResponse
    {
        $this->tournament = Tournament::with('groups')->find($id);

        $playoff = new PlayOff($this->tournament);

        $playoff->setTeamsCountGoesOutFromGroup(4)->create();

        (Tournament::find($id))->generateGames();

        return redirect()->route('tournaments.show', $this->tournament);
    }
}
