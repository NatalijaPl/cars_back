<?php

// namespace App\Http\Controllers;

// use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\Controller;
// use App\Http\Requests\RegisterRequest;
// use App\Models\User;
// use Illuminate\Support\Facades\Hash;

// class AuthController extends Controller
// {
//     /**
//      * Create a new AuthController instance.
//      *
//      * @return void
//      */
//     public function __construct()
//     {
//         $this->middleware('auth:api', ['except' => ['login', 'register']]);
//     }

//     /**
//      * Get a JWT via given credentials.
//      *
//      * @return \Illuminate\Http\JsonResponse
//      */
//     public function login()
//     {
//         $credentials = request(['email', 'password']);

//         if (!$token = auth()->attempt($credentials)) {
//             return response()->json(['error' => 'Unauthorized'], 401);
//         }

//         return $this->responseWithToken($token);
//     }
//     public function logout()
//     {
//         auth()->logout();

//         return response()->json(['message' => 'Successfully logged out']);
//     }
//     public function register(RegisterRequest $request)
//     {
//         $user = User::create([
//             'name' => $request->name,
//             'email' => $request->email,
//             'password' => Hash::make($request->password),
//         ]);

//         $credentials = $request->only(['email', 'password']);

//         if (!$token = auth()->attempt($credentials)) {
//             return response()->json(['error' => 'Unauthorized'], 401);
//         }

//         return response()->json(
//             array_merge(
//                 $this->responseWithToken($token),
//                 ['user' => $user]
//             )
//         );
//     }
//     /**
//      * Get the authenticated User.
//      *
//      * @return \Illuminate\Http\JsonResponse
//      */
//     // public function me()
//     // {
//     //     return response()->json(auth()->user());
//     // }

//     /**
//      * Log the user out (Invalidate the token).
//      *
//      * @return \Illuminate\Http\JsonResponse
//      */


//     /**
//      * Refresh a token.
//      *
//      * @return \Illuminate\Http\JsonResponse
//      */
//     // public function refresh()
//     // {
//     //     return $this->responseWithToken(auth());
//     // }

//     /**
//      * Get the token array structure.
//      *
//      * @param  string $token
//      *
//      * @return \Illuminate\Http\JsonResponse
//      */
//     protected function responseWithToken($token)
//     {
//         return response()->json([
//             'access_token' => $token,
//             'token_type' => 'bearer',
//             'expires_in' => auth()->factory()->getTTL() * 60
//         ]);
//     }
// };

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(RegisterRequest $request)
    {
        $validatedData = $request->validated();
        $user = User::create([
            "name" => $validatedData["name"],
            "email" => $validatedData["email"],
            "password" => Hash::make($validatedData["password"])
        ]);
        $token = auth()->login($user);

        return response()->json([
            "status" => "success",
            "user" => $user,
            "authorization" => [
                "token" => $token
            ]
        ]);
    }

    public function login(LoginRequest $request)
    {
        $validatedData = $request->validated();

        $credentials = [
            "email" => $validatedData['email'],
            "password" => $validatedData['password']
        ];

        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                "status" => "erroe",
                "message" => "Unauthorizes"
            ], 401);
        }

        return response()->json(
            [
                "status" => "success",
                "user" => auth()->user(),
                "authorization" => [
                    "token" => $token,
                ]
            ]
        );
    }

    public function refresh(Request $request)
    {
        return response()->json([
            "status" => "success",
            "user" => Auth::user(),
            "authorization" => [
                "token" => Auth::refresh()
            ]
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json([
            "status" => "success",
        ]);
    }

    public function getActiveUser()
    {
        $activeUser = Auth::user();

        return response()->json($activeUser);
    }
}
