@extends('layouts.app')

@section('content')
    <h1>Teams</h1>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
        </tr>
        </thead>
        <tbody>
        @foreach($teams as $team)
            <tr>
                <th scope="row">{{$team->id}}</th>
                <td><a href="{{route('teams.show', $team->id)}}">{{$team->name}}</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection