<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowsController extends Controller
{
    public function __invoke(User $user)
    {
        // Attach and detach
        $userProfileToFollowOrUnFollow = $user->profile;
        return auth()->user()->following()->toggle($userProfileToFollowOrUnFollow);
    }
}
