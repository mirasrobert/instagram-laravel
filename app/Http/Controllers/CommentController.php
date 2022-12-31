<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $data = $request->validate([
            'content' => 'required|string'
        ]);

        $newComment = $post->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $data['content']
        ]);

        return $newComment;
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return response([
            'message' => 'Comment has been deleted.'
        ], 200);
    }
}
