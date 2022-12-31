<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function index()
    {
        $profiles = Profile::with('user')->get();
        return response($profiles, 200);
    }

    public function show($id)
    {
        $profile = Profile::with(['user' => function ($query) {
            $query->withCount('following');
        }, 'posts.image',])
            ->withCount('followers')
            ->whereHas('user', function ($query) use ($id) {
                $query->where('username', $id);
            })
            ->first();

        if (!$profile) {
            return response([
                'message' => 'Profile not found'
            ], 404);
        }

        // Check if the authenticated user is following this profile
        $follows = auth()->user()->following->contains($profile->id);

        return response([
            'profile' => $profile,
            'follows' => $follows
        ], 200);

    }

    public function update(Request $request, $id)
    {
        $fields = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'username' => ['required', 'string'],
            'image' => ''
        ]);

        $profile = Profile::whereHas('user', function ($query) use ($id) {
            $query->where('username', $id);
        })->first();

        if (!$profile) {
            return response([
                'message' => 'Profile not found'
            ], 404);
        }

        $profile->update([
            'description' => $request->description,
            'website' => $request->website,
        ]);

        $updateUserFields = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
        ];

        $uploadedFileUrl = 'https://t3.ftcdn.net/jpg/02/48/42/64/360_F_248426448_NVKLywWqArG2ADUxDq6QprtIzsF82dMF.jpg';

        if ($request->hasFile('image')) {
            // Upload an image file to cloudinary with one line of code
            // $uploadedFileUrl = cloudinary()->upload($request->file('image')->getRealPath())->getSecurePath();
            $uploadedFileUrl = $request->file('image')->store('uploads', 'public');
            $APP_URL = env('APP_URL', 'http://localhost:8000');
            $uploadedFileUrl = $APP_URL . '/storage/' . $uploadedFileUrl;
            $updateUserFields = array_merge($updateUserFields, ['avatar' => $uploadedFileUrl]);
        }

        auth()->user()->update($updateUserFields);

        return response([
            'message' => 'Profile has been updated.'
        ], 200);

    }

    public function destroy($id)
    {
        $profile = Profile::find($id);

        if (!$profile) {
            return response([
                'message' => 'Profile not found'
            ], 404);
        }

        $profile->delete();

        return response([
            'message' => 'Profile deleted'
        ], 200);

    }

    public function search($search)
    {
        $profiles = Profile::whereHas('user', function ($query) use ($search) {
            $query->where('username', 'like', '%' . $search . '%');
        })
            ->with('user:id,name,username,avatar')
            ->take(5)
            ->get(['id', 'user_id']);

        return response($profiles, 200);
    }

}
