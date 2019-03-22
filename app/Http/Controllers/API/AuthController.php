<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function register()
    {
        User::create([
            'name'     => request('name'),
            'email'    => request('email'),
            'password' => bcrypt(request('password'))
        ]);
        
        return response()->json(['status' => 201]);
    }

    public function login()
    {
        //Check if a user with the specified email exists
        $user = User::whereEmail(request('username'))->first();

        if (! $user) {
            return response()->json([
                'message' => 'Wrong email or password',
                'status'  => 422
            ], 422);
        }

        if (! Hash::check(request('password'), $user->password)) {
            return response()->json([
                'message' => 'Wrong email or password',
                'status'  => 422
            ], 422);
        }

        // Send internal API request
        $client = DB::table('oauth_clients')
            ->where('password_client', true)
            ->first();

        if (! $client) {
            return response()->json([
                'message' => 'Laravel Passport is not setup properly.',
                'status'  => 500
            ], 500);
        }
        
        $data = [
            'grant_type'    => 'password',
            'client_id'     => $client->id,
            'client_secret' => $client->secret,
            'username'      => request('username'),
            'password'      => request('password'), 
        ];


        $request = Request::create('/oauth/token', 'POST', $data);

        $response = app()->handle($request);

        if ($response->getStatusCode() != 200) {
            return response()->json([
                'message' => 'Wrong email or password',
                'status'  => 422
            ], 422);
        }

        $data = json_decode($response->getContent());

        return response()->json([
            'token'  => $data->access_token,
            'user'   => $user,
            'status' => 200,
        ]);
    }

    public function logout()
    {
        $accessToken = auth()->user()->token();

        $refreshToken = DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);
        
        $accessToken->revoke();

        return response()->json(['status' => 200]);
    }

    public function getUser()
    {
        if (! auth()->user()) {
            return response()->json([
                'message' => 'Unauthorized',
                'status'  => 401
            ], 401);
        } else {
            return response()->json([
                'user' => auth()->user(),
                'status'  => 200
            ], 200);
        }
    }
}
