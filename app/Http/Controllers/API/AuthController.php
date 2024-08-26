<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|min:4',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response_code' => '422',
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }
 
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'response_code' => '201',
            'status' => 'success',
            'message' => 'User registered successfully.',
        ], 201);
    }

    /**
     * Login a user and return an access token.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response_code' => '422',
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $accessToken = $user->createToken('Personal Access Token')->accessToken;

            return response()->json([
                'response_code' => '200',
                'status' => 'success',
                'message' => 'Login successful.',
                'user_info' => $user,
                'token' => $accessToken,
            ], 200);
        } else {
            return response()->json([
                'response_code' => '401',
                'status' => 'error',
                'message' => 'Unauthorized. Invalid email or password.',
            ], 401);
        }
    }

    /**
     * Logout the user by revoking the access token.
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'response_code' => '200',
            'status' => 'success',
            'message' => 'Logout successful.',
        ], 200);
    }

    /**
     * Get the list of users.
     */
    public function userDetails()
    {
        try {
            $userDataList = User::latest()->paginate(10);

            return response()->json([
                'response_code' => '200',
                'status' => 'success',
                'message' => 'User list retrieved successfully.',
                'data_user_list' => $userDataList,
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error fetching user list: ' . $e->getMessage());

            return response()->json([
                'response_code' => '500',
                'status' => 'error',
                'message' => 'Failed to retrieve user list.',
            ], 500);
        }
    }
}
