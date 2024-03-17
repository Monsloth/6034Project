<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta 标签确保网站在不同设备上正确渲染，并设置字符集为 UTF-8 -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 页面标题 -->
    <title>Travel Ideas</title>
    <!-- 引入 Font Awesome 图标库和自定义 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <header>
         <!-- 网站标题 -->
        <h1>Welcome to Travel Ideas</h1>
        <!-- 导航菜单 -->
        <nav>
            <ul>
                <!-- 每个菜单项旁边使用 Font Awesome 图标，并使用 title 属性提供悬浮提示 -->
                <li><a href="/" title="Home"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="/register" title="Create User"><i class="fas fa-user-plus"></i> Create User</a></li>
                <li><a href="/login" title="Login"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <li><a href="{{ route('travel_ideas.index') }}" title="Travel Ideas"><i class="fas fa-lightbulb"></i> Travel Ideas</a></li>
            </ul>
        </nav>

    </header>
    <!-- 主要内容区域 -->
    <main>
        <section>
            <h2>Share and Explore Travel Ideas</h2>
            <p>Discover new destinations, share your own travel stories, and get inspired for your next adventure.</p>
        </section>
    </main>
    <footer>
        <!-- 页脚，包含版权信息 -->
        <p>© 2024 Travel Ideas. All rights reserved.</p>
    </footer>
</body>
</html>
