<div class="comment">
    <p>{{ $comment->user->name }}</p>
    <p>{{ $comment->comment }}</p>
    <p>{{ $comment->created_at->format('Y-m-d H:i:s') }}</p>
</div>