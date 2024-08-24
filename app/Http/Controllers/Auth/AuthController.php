<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    public function register(RegisterRequest $request) 
    {
        $user = User::create($request->validated());

        Auth::login($user);

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('API')->plainTextToken,
        ], Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request) 
    {
        if(!Auth::attempt($request->validated())){
            throw ValidationException::withMessages([
                'email' => __("Invalid Email or Password!"),
            ]);
        }

        /** @var User $user */
        $user = Auth::user();

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('API')->plainTextToken,
        ]);
    }

    public function logout(Request $request) 
    {
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}
