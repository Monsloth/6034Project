<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Ideas</title>
    <!-- 可以添加 Bootstrap 或自定义 CSS 以美化页面 -->
    <link href="{{ asset('css/IdeaShow.css') }}" rel="stylesheet">
    @stack('styles') <!-- 为子视图预留位置，以便添加额外的 CSS -->    
</head>
<body>
    <nav class="navbar">
        <div>
            <!-- Navigation Buttons -->
            <a href="{{ route('travel_ideas.create')}}">New Idea</a>
            <a href="/travel_ideas/edit">Edit My Idea</a>
            <a href="/logout">Logout</a>
        </div>
    </nav>

<main>
    @yield('content')
</main>
</body>
</html>
