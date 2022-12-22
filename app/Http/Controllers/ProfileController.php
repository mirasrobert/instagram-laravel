<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{

    public function index()
    {
        $profiles = Profile::with('user')->get();
        return response($profiles, 200);
    }

    public function show($username)
    {
        $profile = Profile::with(['user', 'posts.image'])
            ->whereHas('user', function ($query) use ($username) {
                $query->where('username', $username);
            })
            ->first();

        if(!$profile) {
            return response([
                'message' => 'Profile not found'
            ], 404);
        }

        return response($profile, 200);

    }

    public function update(Request $request, $id)
    {
        $profile = Profile::with('user')->find($id);

        if(!$profile) {
            return response([
                'message' => 'Profile not found'
            ], 404);
        }

        return response([
            'message' => 'Update coming soon...'
        ]);

    }

    public function destroy($id)
    {
        $profile = Profile::find($id);

        if(!$profile) {
            return response([
                'message' => 'Profile not found'
            ], 404);
        }

        $profile->delete();

        return response([
            'message' => 'Profile deleted'
        ], 200);

    }

}
