<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthApiController extends Controller
{
    //

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->all())) {
            $user = User::where('email', $request->email)->first();
            $token =  $user->createToken('admin-login');


            return successResponse($user, 200, '', ['token' => $token->plainTextToken]);

            // $user->tokens()->delete(); 
        }
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return successResponse([], 200, 'Logout Successfull');
    }
}
