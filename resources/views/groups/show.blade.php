@extends('layouts.app')

@section('content')
    <h1>Group: {{$group->name}}</h1>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Team</th>
        </tr>
        </thead>
        <tbody>
        @foreach($group->teams as $team)
            <tr>
                <th scope="row">{{$team->id}}</th>
                <td><a href="{{route('teams.show', $team->id)}}">{{$team->name}}</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection