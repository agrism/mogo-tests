@extends('layouts.app')

@section('content')
    <h1>Tournaments</h1>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tournaments as $tournament)
            <tr>
                <th scope="row">{{$tournament->id}}</th>
                <td><a href="{{route('tournaments.show', $tournament->id)}}">{{$tournament->name}}</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <a class="btn btn-primary" href="{{route('tournaments.create')}}" role="button">Create new tournament</a>

@endsection