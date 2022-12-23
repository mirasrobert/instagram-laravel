<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Post;
use App\Models\Profile;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        // This will get all the user_id in profiles table using the many-to-many relationship
        // Return [1, 2, 3] array of user_id
        $following = auth()->user()->following()->pluck('profiles.user_id');
        $posts = Post::whereIn('profile_id', $following)
            ->with(['profile.user', 'image'])
            ->latest()
            ->get();
        return response($posts, 200);
    }

    public function show($id)
    {
        $post = Post::find($id);

        if(!$post) {
            return response([
                'message' => 'Post not found'
            ], 404);
        }

        return response($post, 200);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'caption' => ['required', 'string'],
            'image' => ['required']
        ]);

        $newPost = Post::create([
            'profile_id' => $request->user()->profile->id,
            'caption' => $request->caption,
        ]);

        $uploadedFileUrl = 'https://t3.ftcdn.net/jpg/02/48/42/64/360_F_248426448_NVKLywWqArG2ADUxDq6QprtIzsF82dMF.jpg';

        if ($request->hasFile('image')) {
            // Upload an image file to cloudinary with one line of code
            // $uploadedFileUrl = cloudinary()->upload($request->file('image')->getRealPath())->getSecurePath();
            $uploadedFileUrl = $request->file('image')->store('uploads', 'public');
            $uploadedFileUrl = '/storage/' . $uploadedFileUrl;
        }

        Image::create([
            'imageable_id' => $newPost->id,
            'imageable_type' => 'App\Models\Post',
            'url' => $uploadedFileUrl,
        ]);

        return response($newPost, 201);
    }

    public function destroy($id)
    {
        $post = Post::find($id);

        if(!$post) {
            return response([
                'message' => 'Post not found'
            ], 404);
        }

        $post->delete();

        return response([
            'message' => 'Post deleted'
        ], 200);
    }
}
