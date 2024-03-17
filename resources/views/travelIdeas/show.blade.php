@extends('layouts.BookLayout')

@section('content')
<!-- 将数据库内的信息循环展示出来 -->
<link rel="stylesheet" href="{{ asset('css/IdeaShow.css') }}">
<div class="user-ideas">
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Destination</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Tags</th>
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
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="pagination">
    {{ $travelIdeas->links() }}
</div>
@endsection
