<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Travel Idea</title>
    <style>
        /* 样式代码 */
        .comment small {
            font-size: 12px; /* 调整为您希望的时间戳字体大小 */
        }

        .comment small.username {
            font-size: 14px; /* 调整为您希望的用户名字体大小 */
        }

        .comment p {
            font-size: 10px; /* 调整为您希望的评论内容字体大小 */
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // 处理评论表单的提交
            $('#comment-form').submit(function(e) {
                e.preventDefault(); // 阻止表单默认提交行为

                var formData = $(this).serialize(); // 序列化表单数据
                var url = $(this).attr('action'); // 获取表单的提交 URL

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: formData,
                    success: function(response) {
                        // 处理成功响应，例如清空表单、动态添加新评论等
                    },
                    error: function(error) {
                        // 处理错误响应
                    }
                });
            });
        });
    </script>
</head>
<body>
    <h2>{{ $travelIdea->title }}</h2>
    <p>{{ $travelIdea->description }}</p>

    <div id="comments">
        @foreach ($comments->sortByDesc('created_at') as $comment)
            <div class="comment">
                <small class="username">{{ $comment->user->name }}</small>
                <small>{{ $comment->created_at->format($comment->created_at->isToday() ? 'H:i:s' : 'Y-m-d H:i:s') }}</small>
                <p>{{ $comment->comment }}</p>
            </div>
        @endforeach
    </div>

    <form id="comment-form" action="{{ route('comments.store') }}" method="post">
        @csrf
        <input type="hidden" name="travel_idea_id" value="{{ $travelIdea->id }}">
        <textarea name="comment" maxlength="255" required></textarea>
        <button type="submit">Submit Comment</button>
    </form>
</body>
</html>