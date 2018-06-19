@extends('layouts.app')

@section('content')
    <h1>Tournament: {{$tournament->name}}</h1>

    <?php $color = ['red', '#E1A4DA', '#DAC390', '#D09E71', '#ACC44F', '#C26649', 'red', 'grey']?>


    {{--<h3>Groups details</h3>--}}
    <table style="margin: auto">
        <tbody>
        <tr>
            <td>
                <table style="margin: 0 auto;">
                    @foreach($tournament->groups as $group)

                        <thead style="">
                        <tr style=" height: 80px;">
                            <td colspan="10" style="vertical-align: bottom;">
                                <a href="{{route('groups.show', $group->id)}}"
                                   class="text-uppercase">
                                    Round: {{$group->pivot->level}}, Group: {{$group->name}}
                                </a>
                                <a class="btn btn-primary btn-sm float-right"
                                   href="{{route('groups.generate-results', $group)}}" role="button">Generate Group
                                    results</a>
                            </td>
                        </tr>
                        </thead>


                        <tbody class="table  table-bordered" style="background-color: {{$color[$group->pivot->level]}}">

                        <tr>
                            <td>Team</td>
                            @foreach($group->teams as $team)
                                <td><a href="{{route('teams.show', $team)}}">{{substr($team->name, 0,3)}}</a></td>
                            @endforeach
                            <td>Points</td>
                        </tr>


                        @foreach($group->teams as $index => $team)
                            <tr>
                                {{--<th scope="row"></th>--}}
                                <td><a href="{{route('teams.show', $team->id)}}" class="float-left">{{$team->name}}</a>
                                </td>


                                @foreach($team->games as $index => $game)

                                    @if($game->home_team_id == $team->id || $game->guest_team_id == $team->id)

                                        @if($game->id === 0)
                                            <td style="background-color: #585858">
                                            </td>
                                        @else
                                            <td>
                                                @if($game->home_team_id == $team->id)
                                                    {{$game->home_team_point}}:{{$game->guest_team_point}}
                                                @else
                                                    {{$game->guest_team_point}}:{{$game->home_team_point}}
                                                @endif
                                            </td>
                                        @endif


                                        @if(!isset($team->games[($index+1)]))
                                            <td>
                                                {{$team->score}}
                                            </td>
                                        @endif

                                    @endif
                                @endforeach
                            </tr>
                    @endforeach

                    {{--                        @foreach($group->teams->sortBy('id')->sortByDesc('score') as $index => $team)--}}
                    @if($group->teams->count()>2)
                        <tr style="background-color: white">
                            <td>Team</td>
                            <td>id</td>
                            <td>Points</td>
                        </tr>
                        @foreach($group->getRankedTeams() as $index => $team)
                            <tr style="background-color: white">
                                <td>{{$team->name}}</td>
                                <td>#{{$team->id}}</td>
                                <td>{{$team->points}}</td>
                            </tr>

                        @endforeach
                        </tbody>
                        @endif

                        </tbody>

                        @endforeach
                </table>
            </td>
        </tr>

        </tbody>
    </table>

    @if(isset($showGeneratePlayoffNextButton) && $showGeneratePlayoffNextButton)
        <a class="btn btn-primary" style="margin: 50px 0px 50px 0px" href="{{route('generatePlayoff', $tournament)}}">Gener.
            Playoff next round</a>
    @endif

    <h3 style="margin-top: 30px">Results</h3>
    <table class="table">
        @foreach($tournament->groups as $group)
            @foreach($group->games as $game)
                <tr>
                    <td>{{!isset($gameIndex) ? $gameIndex=1 : ++$gameIndex}}</td>
                    <td>{{$game->homeTeam->name}}</td>
                    <td>{{$game->guestTeam->name}}</td>
                    @if($game->home_team_point!==null)
                        <td>{{$game->home_team_point}}:{{$game->guest_team_point}}</td>
                    @else
                        <td>comming...</td>
                    @endif
                </tr>
            @endforeach
        @endforeach
    </table>
@endsection