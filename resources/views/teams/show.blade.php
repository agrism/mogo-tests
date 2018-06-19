@extends('layouts.app')

@section('content')
    <h1>Team: {{$team->name}}</h1>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">info</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

@endsection