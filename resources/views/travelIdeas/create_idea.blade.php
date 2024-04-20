<!-- @extends('layouts.BookLayout') -->
@section('content')
@push('styles')
    <link href="{{ asset('css/create.css') }}" rel="stylesheet">
@endpush
    <div class="container">
        <h1>New Travel Idea</h1>
        <form id=create action="/travel_ideas" method="POST">
            @csrf <!-- CSRF 令牌用于保护表单 -->
            
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="destination">Destination:</label>
                <input type="text" id="destination" name="destination" class="form-control" required>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="text" id="start_date" name="start_date" class="form-control datepicker" required>
            </div>

            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="text" id="end_date" name="end_date" class="form-control datepicker" required>
            </div>

            <div class="form-group">
                <label for="tags">Tags:</label>
                <input type="text" id="tags" name="tags" class="form-control" placeholder="e.g., beach, hiking, city tour">
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
<script>
    $('#create').on('submit', function(e) {
    e.preventDefault(); // 阻止表单的默认提交行为

    var formData = $(this).serialize(); // 序列化表单数据

    $.ajax({
        type: 'POST',
        url: '{{ route("travel_ideas.store") }}',
        data: formData,
        success: function(response) {
            // 清空城市输入框并显示成功消息
            $('#destination').val('');
            alert(response.success);
        },
        error: function(xhr, status, error) {
            // 如果是验证错误，只清空城市输入框
            if (xhr.status === 422) {
                $('#destination').val('');
                alert(xhr.responseJSON.error);
            } else {
                // 处理其他错误
                console.error(error);
            }
        }
    });
});
</script>
    <!-- jQuery 和 jQuery UI JavaScript，用于日期选择器功能 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function() {
            // 初始化 jQuery 日期选择器
            $('.datepicker').datepicker({
                dateFormat: 'yy-mm-dd' // 设置日期格式
            });
        });
    </script>
@endsection

