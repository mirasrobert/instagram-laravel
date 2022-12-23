<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        $fields = request()->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'name' => ['required', 'string'],
            'username' => ['required', 'string', 'unique:users,username'],
            'password' => ['required', 'string', 'confirmed']
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'username' =>  $fields['username'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'avatar' => 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y'
        ]);

        // After creating an account
        // Set a default profile
        $user->profile()->create([
           'description' => 'Hi there :)'
        ]);

        $token = $user->createToken(config('sanctum.SANCTUM_SECRET_TOKEN'))->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);


    }
}
