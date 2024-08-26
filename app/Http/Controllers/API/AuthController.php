<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Carbon\Carbon;
use OpenApi\Annotations as OA; 

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     description="User model",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="User ID"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="User's name"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         description="User's email"
 *     ),
 *     @OA\Property(
 *         property="email_verified_at",
 *         type="date",
 *         description="Date when email was verified"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         description="User's password"
 *     ),
 *     @OA\Property(
 *         property="remember_token",
 *         type="string",
 *         description="Persisten token for the user"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Timestamp when the user was created"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Timestamp when the user was last updated"
 *     )
 * )
 *
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Clark"),
     *             @OA\Property(property="email", type="string", example="clark@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="response_code", type="string", example="201"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="User registered successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="response_code", type="string", example="422"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="The email field is required.")
     *         )
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login a user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="clark@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="response_code", type="string", example="200"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Login successful."),
     *             @OA\Property(property="user_info", type="object", ref="#/components/schemas/User"),
     *             @OA\Property(property="token", type="string", example="access_token_here")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="response_code", type="string", example="422"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="The email field is required.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="response_code", type="string", example="401"),
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthorized. Invalid email or password.")
     *         )
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Logout the user",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="response_code", type="string", example="200"),
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Logout successful.")
     *         )
     *     )
     * )
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
}
