<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Ideas</title>
    <!-- 可以添加 Bootstrap 或自定义 CSS 以美化页面 -->
    <link href="{{ asset('css/IdeaNav.css') }}" rel="stylesheet">
    @stack('styles') <!-- 为子视图预留位置，以便添加额外的 CSS -->    
</head>
<body>
    <nav class="navbar d-flex justify-content-center">
        <div>
            <!-- Navigation Buttons -->
            <a href="{{route('index')}}">Home Page </a>
            <a href="{{route('travel_ideas.index')}}">All ideas</a>
            <a href="{{ route('travel_ideas.create')}}">New Idea</a>
            <a href="{{ route('travel_ideas.my_ideas') }}">Edit My Idea</a>
            <a href="/logout">Logout</a>
        </div>
    </nav>

<main>
    @yield('content')
</main>
</body>
</html>
