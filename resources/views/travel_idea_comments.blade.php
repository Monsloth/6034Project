<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Travel Idea</title>
    <meta name="description" content="Travel Idea" />
    <link rel="stylesheet" href="/css/comment.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Process submission of the comment form
            $('#comment-form').submit(function(e) {
                check_login()
                e.preventDefault(); // Prevents form default submission behavior

                var formData = $(this).serializeArray(); // 序列化表单数据
                var url = $(this).attr('action'); // 获取表单的提交 URL
                // 添加额外的键值对，确保包含 "user_id"、"idea_id" 和 "comment" 字段
                formData.push({name: 'user_id', value: $('[name="user_id"]').val()}); // 确保字段名称为 "user_id"
                formData.push({name: 'idea_id', value: $('[name="idea_id"]').val()});
                formData.push({name: 'comment', value: $('[name="comment"]').val()});

                $.ajax({
                    type: 'POST',
                    // url: '/travel_ideas/' + {id} + '/comments',
                    url: url,
                    data: formData,
                    cache: false,
                    success: function(response) {

                        // 清空评论框
                        $('#comment-form textarea[name="comment"]').val('');

                        // 加载更新后的评论日志
                        loadLog();

                        // 自动滚动到聊天框顶部
                        var newscrollHeight = 0;
                        $("#chatbox").animate({scrollTop: newscrollHeight}, 'normal');
                    },

                    error: function(error) {
                        // 处理错误响应
                        alert('Error: ' + error.responseText);
                    }
                });

                return false; // 阻止表单默认提交行为
            });
            

            // 定期重新加载聊天记录
            setInterval(loadLog, 2500);              
                
        });
        var id = {{ $travelIdea->id }};
        function loadLog() {
                    //refresh_nums()
                    $.ajax({
                        url: '/travel_ideas/' + id + '/comments',
                        cache: false,
                        success: function(html) {
                            // 仅更新聊天记录部分
                            $("#comments .comment").remove(); // 清空之前的聊天记录
                            $("#comments").append($(html).find('.comment')); // 添加新的聊天记录
                            $("#comment-count-text").remove(); // 清空之前的数量记录
                            $("#comment-count").append($(html).find('#comment-count-text')); // 添加新的数量记录
                        },
                    });
                }
        function check_login(){
            var user_id = $("#user_id").val()
                if (user_id == null || user_id == undefined){
                    // if user_id is null
                    alert('please login first!')
                    window.location.href='http://localhost:8000'    //重定向到指定网址
                }
        }

               
    </script>
</head>
<body>
    <div id="header">
        <h1>Let's Chat!</h1>
        <img src="/images/image1.png" alt="Header Image">
    </div>
    <h2>{{ $travelIdea->title }}</h2>
    <p>Destination: {{ $travelIdea->destination }}</p>
    <p>Travel Date: {{ $travelIdea->start_date }} to {{ $travelIdea->end_date }}</p>
    <p>Tags: {{ $travelIdea->tags }}</p>
    <p>Author: {{ $travelIdea->user_name }}</p>
    <div id="comment-count">
        <p id="comment-count-text">Total {{ $comments->count() }} comment(s)</p>
    </div>
    <div id="chatbox">        
        <div id="comments">
            @foreach ($comments->sortByDesc('created_at') as $comment)
                <div class="comment">
                    <small class="username">{{ $comment->user->name }}</small>
                    <small>{{ $comment->created_at->format($comment->created_at->isToday() ? 'H:i:s' : 'Y-m-d H:i:s') }}</small>
                    <p>{{ $comment->comment }}</p>
                </div>
            @endforeach
        </div>
    </div>
    <div>
        <button type="button" onclick="loadLog()">refresh</button>
    </div>
    <p>Your comments: </p>
    <form id="comment-form" action="{{ route('comments.store') }}" method="post">
    @csrf
    @if (Auth::check())
    <input type="hidden" id="user_id" name="user_id" value="{{ Auth::user()->id }}">
    @endif
    <input type="hidden" name="idea_id" value="{{ $travelIdea->id }}">
    <textarea name="comment" maxlength="255" placeholder="no more than 255 characters" required></textarea>
    <button type="submit">send</button>
    </form>
</body>
</html>