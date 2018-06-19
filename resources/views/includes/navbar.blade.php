<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="{{route('home')}}">Tournaments</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            {{--<li class="nav-item active">--}}
                {{--<a class="nav-link" href="{{route('home')}}">Home <span class="sr-only">(current)</span></a>--}}
            {{--</li>--}}
            <li class="nav-item {{strpos(request()->route()->getName(), 'tournaments') ===0 ? 'active' : null }}">
                <a class="nav-link" href="{{route('tournaments.index')}}">Tournaments</a>

            </li>
            <li class="nav-item {{strpos(request()->route()->getName(), 'teams') ===0 ? 'active' : null }}">
                <a class="nav-link" href="{{route('teams.index')}}">Teams</a>
            </li>

            {{--<li class="nav-item">--}}
                {{--<a class="nav-link disabled" href="#">Disabled</a>--}}
            {{--</li>--}}
            {{--<li class="nav-item dropdown">--}}
                {{--<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Dropdown</a>--}}
                {{--<div class="dropdown-menu" aria-labelledby="dropdown01">--}}
                    {{--<a class="dropdown-item" href="#">Comming soom</a>--}}
                    {{--<a class="dropdown-item" href="#">Comming soom</a>--}}
                    {{--<a class="dropdown-item" href="#">Comming soom</a>--}}
                {{--</div>--}}
            {{--</li>--}}
        </ul>
    </div>
</nav>