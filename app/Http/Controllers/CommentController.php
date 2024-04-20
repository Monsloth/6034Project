<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\TravelIdea;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // public function showComments()
    // {
    //     $comments = Comment::orderByDesc('created_at')->get();
    //     return view('travel_idea_comments', compact('comments'));
    // }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'idea_id' => 'required|exists:travel_ideas,id',
            'user_id' => 'required|exists:users,id',
            'comment' => 'required|max:255',
        ]);

        // create comment
        $comment = Comment::create([
            'idea_id' => $validatedData['idea_id'],
            'user_id' => $validatedData['user_id'],
            'comment' => $validatedData['comment'],
        ]);

        // Return an HTML fragment of the new comment using AJAX techniques
        if ($request->ajax()) {
            return view('partials.comment', ['comment' => $comment])->render();
        }
    }

    // add comments
    public function showComments($id)
    {
        $travelIdea = TravelIdea::findOrFail($id);
        $comments = Comment::where('idea_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('travel_idea_comments', compact('travelIdea', 'comments'));


    }

}