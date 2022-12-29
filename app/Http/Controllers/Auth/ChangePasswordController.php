<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $user = auth()->user();

        // Check password
        if (!$user || !Hash::check($request->current_password, $user->password)) {
            return response([
                'bad_request_message' => 'Current password is incorrect.'
            ], 401);
        }

        $user->update([
            'password' => bcrypt($request->password)
        ]);

        return response([
            'message' => 'Password has been updated.'
        ], 200);

    }
}
