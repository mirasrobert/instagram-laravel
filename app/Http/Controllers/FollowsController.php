<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;

class FollowsController extends Controller
{
    public function store(User $user)
    {
        // Attach and detach
        $userProfileToFollowOrUnFollow = $user->profile;
        return auth()->user()->following()->toggle($userProfileToFollowOrUnFollow);
    }

    public function following()
    {
        // This will get all the user_id in profiles table using the many-to-many relationship
        // Return [1, 2, 3] array of user_id
        $following = auth()->user()->following()->pluck('profiles.user_id');
        $users = User::whereIn('id', $following)->get();
        return response($users, 200);
    }
}
