<?php

// namespace App\Http\Controllers;

// use App\Models\Userclient;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\Storage;

// class UserclientController extends Controller
// {
//     public function register(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'name' => 'required|string|max:255',
//             'email' => 'required|string|email|max:255|unique:userclients',
//             'password' => 'required|string|min:6',
//         ]);

//         if ($validator->fails()) {
//             return response()->json($validator->errors(), 422);
//         }

//         $user = Userclient::create([
//             'name' => $request->name,
//             'email' => $request->email,
//             'password' => bcrypt($request->password),
//         ]);

//         return response()->json(['message' => 'Registration successful', 'user' => $user], 201);
//     }

//     public function login(Request $request)
//     {
//         $credentials = $request->only('email', 'password');

//         if (Auth::guard('client')->attempt($credentials)) {
//             $user = Auth::guard('client')->user();
//             return response()->json(['message' => 'Login successful', 'user' => $user], 200);
//         } else {
//             return response()->json(['message' => 'Invalid credentials'], 401);
//         }
//     }

//     public function update(Request $request)
//     {
//         $user = Auth::guard('client')->user();

//         $validator = Validator::make($request->all(), [
//             'name' => 'required|string|max:255',
//             'username' => 'nullable|string|max:255',
//             'gender' => 'nullable|string|max:255',
//             'city' => 'nullable|string|max:255',
//             'cin' => 'nullable|string|max:255',
//             'phone' => 'nullable|string|max:255',
//             'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
//         ]);

//         if ($validator->fails()) {
//             return response()->json($validator->errors(), 422);
//         }

//         $user->update([
//             'name' => $request->name,
//             'username' => $request->username,
//             'gender' => $request->gender,
//             'city' => $request->city,
//             'cin' => $request->cin,
//             'phone' => $request->phone,
//         ]);

//         if ($request->hasFile('image')) {
//             $path = $request->file('image')->store('images', 'public');
//             $user->update(['image' => $path]);
//         }

//         return response()->json(['message' => 'Profile updated successfully', 'user' => $user], 200);
//     }
// }














namespace App\Http\Controllers;

use App\Models\Userclient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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

        return response()->json(['message' => 'Registration successful', 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('client')->attempt($credentials)) {
            $user = Auth::guard('client')->user();
            return response()->json(['message' => 'Login successful', 'user' => $user], 200);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }
    public function editProfile()
    {
        $user = Auth::guard('client')->user();

        return view('profile', compact('user'));
    }
    // public function updateProfile(Request $request)
    // {
    //     $userId = $request->query('userId');
    //     $user = Userclient::find($userId);

    //     if (!$user) {
    //         return response()->json(['message' => 'User not found'], 404);
    //     }

    //     $validator = Validator::make($request->all(), [
    //         'name' => 'nullable|string|max:255',
    //         'email' => 'nullable|string|email|max:255|unique:userclients,email,' . $user->id,
    //         'password' => 'nullable|string|min:6',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 422);
    //     }

    //     // Update the user's profile fields
    //     $user->name = $request->input('name', $user->name);
    //     if ($request->has('email')) {
    //         $user->email = $request->email;
    //     }
    //     if ($request->has('password')) {
    //         $user->password = bcrypt($request->password);
    //     }
    //     $user->save();

    //     return response()->json(['message' => 'Profile updated successfully', 'user' => $user], 200);
    // }

    public function updateProfile(Request $request)
    {
        $userId = $request->query('userId');
        $user = Userclient::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:userclients,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'username' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'cin' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update the user's profile fields
        $user->name = $request->input('name', $user->name);
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->username = $request->input('username', $user->username);
        $user->gender = $request->input('gender', $user->gender);
        $user->city = $request->input('city', $user->city);
        $user->cin = $request->input('cin', $user->cin);
        $user->phone = $request->input('phone', $user->phone);
        $user->image = $request->input('image', $user->image);
        $user->save();

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user], 200);
    }
}
