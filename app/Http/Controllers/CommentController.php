<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'travel_idea_id' => 'required|exists:travel_ideas,id',
            'comment' => 'required|max:255',
        ]);

        // 创建评论
        $comment = Comment::create([
            'idea_id' => $validatedData['travel_idea_id'],
            'user_id' => auth()->id(),
            'comment' => $validatedData['comment'],
        ]);

        // 使用 AJAX 技术返回新评论的 HTML 片段
        if ($request->ajax()) {
            return view('partials.comment', ['comment' => $comment])->render();
        }
    }
}