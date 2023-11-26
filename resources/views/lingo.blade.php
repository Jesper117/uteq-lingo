<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Uteq Lingo</title>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="icon" href="{{ asset('img/uteq-favicon.png') }}" type="image/x-icon"/>

        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script src="{{ asset('js/lingo.js') }}"></script>
    </head>
    <body>
        <div class="logo">
            <img src="{{ asset('img/logo.png') }}" alt="logo">
        </div>

        <div class="game-container">
            <div id="container">
                <div class="row1">
                    <div class="square default"></div>
                    <div class="square default"></div>
                    <div class="square default"></div>
                    <div class="square default"></div>
                    <div class="square default"></div>
                </div>
                <div class="row2">
                    <div class="square default"></div>
                    <div class="square default"></div>
                    <div class="square default"></div>
                    <div class="square default"></div>
                    <div class="square default"></div>
                </div>
                <div class="row3">
                    <div class="square default"></div>
                    <div class="square default"></div>
                    <div class="square default"></div>
                    <div class="square default"></div>
                    <div class="square default"></div>
                </div>
                <div class="row4">
                    <div class="square default"></div>
                    <div class="square default"></div>
                    <div class="square default"></div>
                    <div class="square default"></div>
                    <div class="square default"></div>
                </div>
                <div class="row5">
                    <div class="square default"></div>
                    <div class="square default"></div>
                    <div class="square default"></div>
                    <div class="square default"></div>
                    <div class="square default"></div>
                </div>
            </div>
        </div>

        <input class="inputfield" placeholder="Typ je gok!">
    </body>
</html>

<script>
    const CSRF = '{{ csrf_token() }}';
</script>
