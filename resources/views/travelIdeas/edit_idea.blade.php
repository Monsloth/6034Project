@extends('layouts.BookLayout')
@section('content')
@push('styles')
    <link href="{{ asset('css/create.css') }}" rel="stylesheet">
@endpush

    <div class="container">
        <h1>Edit Your Travel Idea</h1>
        <form action="{{ route('travel_ideas.update', $idea->idea_id) }}" method="POST">
            @csrf <!-- CSRF 令牌用于保护表单 -->
            @method('PUT')
            
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ $idea->title }}" required>
            </div>

            <div class="form-group">
                <label for="destination">Destination:</label>
                <input type="text" id="destination" name="destination" class="form-control" value="{{ $idea->destination }}" required>
            </div>

            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="text" id="start_date" name="start_date" class="form-control datepicker" value="{{ $idea->start_date }}"required>
            </div>

            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="text" id="end_date" name="end_date" class="form-control datepicker" value="{{ $idea->end_date }}" required>
            </div>

            <div class="form-group">
                <label for="tags">Tags:</label>
                <input type="text" id="tags" name="tags" class="form-control" value="{{ $idea->tags }}" placeholder="e.g., beach, hiking, city tour">
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success">Update Idea</button>
                <br>
                <a class="btn btn-secondary" href="{{ route('travel_ideas.my_ideas') }}">Cancel</a>
            </div>

        </form>
    </div>

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