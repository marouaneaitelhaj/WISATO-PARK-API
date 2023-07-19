<?php





namespace App\Http\Controllers;

use App\Models\Userclient;
use App\User;
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
    public function showRegistrationForm()
    {
        return view('register');
    }
    public function editProfile()
    {
        $user = Auth::guard('client')->user();

        return view('profile', compact('user'));
    }




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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
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

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('public/images', $fileName); // Update the storage path
                $user->image = 'images/' . $fileName;
            }

            $user->save();

            return response()->json(['message' => 'Profile updated successfully', 'user' => $user], 200);
        } catch (\Exception $error) {
            return response()->json(['message' => $error->getMessage()], 500);
        }
    }
    public function getProfileImage(Request $request)
    {
        $userId = $request->query('userId');
        $user = Userclient::find($userId);

        if (!$user || !$user->image) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        $imagePath = Storage::url($user->image);

        return response()->json(['image' => $imagePath], 200);
    }
    public function getUser()
    {
        return Auth::guard('client')->user();
    }
}
