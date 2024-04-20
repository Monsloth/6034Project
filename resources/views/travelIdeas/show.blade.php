@extends('layouts.BookLayout')

@section('content')
<!-- 将数据库内的信息循环展示出来 -->
<link rel="stylesheet" href="{{ asset('css/IdeaShow.css') }}">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<div class="user-ideas">
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Destination</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Tags</th>
                <th>Comments</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($travelIdeas as $idea)
                <tr>
                    <td>{{ $idea->title }}</td>
                    <td>{{ $idea->destination }}</td>
                    <td>{{ $idea->start_date }}</td>
                    <td>{{ $idea->end_date }}</td>
                    <td>{{ $idea->tags }}</td>
                    <td><a href="{{ route('comments.show', $idea->id)}}">comments</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-center">
    {{ $travelIdeas->links() }}
</div>
@endsection
