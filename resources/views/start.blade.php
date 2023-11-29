<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Uteq Lingo</title>

    <link rel="icon" href="{{ asset('img/uteq-favicon.png') }}" type="image/x-icon"/>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <script src="{{ asset('js/sounds.js') }}"></script>
</head>
<body>
<div class="logo">
    <img draggable="false" src="{{ asset('img/logo.png') }}" alt="logo">
</div>
<div class="game-container">
    <form class="container start-container" method="GET" action="{{url("play")}}">
        @csrf
        <input class="name-input" name="name" placeholder="Naam">
        <button type="submit" class="start-button">Start</button>
    </form>
</div>
<br>
<div class="game-container">
    <div class="container highscore-container">
        <div class="highscore">
            <h1>Highscores</h1>
            <ol class="highscore-list">
                <!--<li class="highscore-item"><i>Hardcoded test</i>: &nbsp; <b>540</b></li>-->
                @foreach($highscores as $highscore)
                    <li class="highscore-item"><i>{{$highscore->name}}</i>: &nbsp; <b>{{$highscore->score}}</b></li>
                @endforeach
            </ol>
        </div>
    </div>
</div>
</body>
</html>

<script>
    PlaySound("MenuMusic", true);
</script>
