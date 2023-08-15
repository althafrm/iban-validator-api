<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users|max:255',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            return response()->json(
                [
                    'success' => true,
                    'user' => $user,
                    'message' => 'Registration successful',
                ],
                201
            );
        } catch (\Illuminate\Validation\ValidationException $validationException) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed. Please check your input.'
            ], 422);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'error' => 'An error occurred during registration.'
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'error' => 'The provided credentials are incorrect.',
                ], 401);
            }

            $token = $user->createToken('spa-token')->plainTextToken;

            return response()->json(
                [
                    'success' => true,
                    'token' => $token,
                    'user' => $user,
                    'message' => 'Logged in successfully',
                ],
                200
            );
        } catch (\Illuminate\Validation\ValidationException $validationException) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed. Please check your input.'
            ], 422);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'error' => 'An error occurred during login.'
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'error' => 'An error occurred during logout.'
            ], 500);
        }
    }
}
