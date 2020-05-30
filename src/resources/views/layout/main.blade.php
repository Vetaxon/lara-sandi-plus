<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
</head>

<body>
<div class="col-1">
    <select class="browser-default custom-select" id="select-lang">
        @foreach($languages as $lang)
            <option value="{{$lang}}">{{$lang}}</option>
        @endforeach
    </select>
</div>
@yield('content')
</body>
<script src="{{ mix('/js/app.js') }}"></script>
</html>
