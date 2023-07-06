<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Laravel\Sanctum\PersonalAccessToken;

class AccessToTokensController extends Controller
{
    public function store(Request $request)
    {

        $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'device_name' => ['string', 'max:255'],
            'abilities' => ['nullable', 'array'],
        ]);


        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {

            // userAgent return platform or mobile information mean it's default value if was not found felid device_name from request
            $device_name = $request->post('device_name', $request->userAgent());
            // this method createToken() from class HasApiTokens and it's use HasApiTokens in model user
            // Here return object
            $token = $user->createToken($device_name, $request->post('abilities'));
            return Response::json([
                // code here from me 1 mean success and 0 mean fail
                'code' => 1,
                'token' => $token->plainTextToken,
                'user' => $user,
            ], 201);
        }

        return Response::json([
            'code' => 0,
            'message' => 'Invalid credentials',
        ], 401);
    }


    // this's function for delete specific token follow for current authentication user
    public function destroy($token = null)
    {
        $user = Auth::guard('sanctum')->user();

        // this's for revoke all tokens
        // tokens() it's relation between User model and HasApiTokens
        // $user->tokens()->delete();

        if (null === $token) {
            // currentAccessToken() this's in class HasApiTokens came when make use for HasApiTokens
            $user->currentAccessToken()->delete();
            return;
        }

        $personal_access_token = PersonalAccessToken::findToken($token);
        // get_class() I using it for return class name
        if ($user->id == $personal_access_token->tokenable_id && get_class($user) == $personal_access_token->tokenable_type) {
            $personal_access_token->delete();
        }
    }
}
