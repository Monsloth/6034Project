<!-- 这个是针对于已经创建的ideas要再编辑的模板 -->
@extends('layouts.BookLayout')

@section('content')
<link rel="stylesheet" href="{{ asset('css/EditShow.css') }}">
<div class="container">
    <h2>Your Travel Ideas</h2>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Destination</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Tags</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($userIdeas as $idea)
                <tr>
                    <td>{{ $idea->title }}</td>
                    <td>{{ $idea->destination }}</td>
                    <td>{{ $idea->start_date }}</td>
                    <td>{{ $idea->end_date }}</td>
                    <td>{{ $idea->tags }}</td>
                    <!-- 编辑按钮 -->
                    <td><a href="{{ route('travel_ideas.edit', $idea->idea_id) }}">Edit</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
