<?php

namespace App\Http\Controllers;

use App\Models\Userclient;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserclientController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:userclients',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = Userclient::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if ($user) {
            return response()->json([
                'token' => $user->createToken(time())->plainTextToken,
            ], 201);
        }
        return response()->json(['message' => 'Registration failed'], 400);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('client')->attempt($credentials)) {
            $user = Auth::guard('client')->user();
            return response()->json([
                'message' => 'marwane',
                'user' => $user,
                'token' => Userclient::find($user->id)->createToken(time())->plainTextToken,
            ], 200);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }

    public function getUser()
    {
        return auth()->user();
    }
}
