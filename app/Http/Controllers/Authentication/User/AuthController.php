<?php

namespace App\Http\Controllers\Authentication\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Authentication\AuthUserRequest;

class AuthController extends Controller
{
    use ResponseTrait;
    /**
     * Authenticates an user user and generates an API token.
     * Validates the login credentials, and if successful, returns an authentication token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AuthUserRequest $request)
    {
        $request->validate($request->loginRules());

        $user = User::where('phone', $request->phone)->first();

        if($user && Hash::check($request->password , $user->password))
        {
            $token = $user->createToken('User-Token' , ['user']);

            return $this->successResponse(
                [
                    'user' => $user,
                    'token' => $token->plainTextToken,
                ],
                'User login successfully.'
            );
        }
        return $this->errorResponse(message:'Invalid credentials. Please check your phone and password.' , statusCode :401);
    }

    /**
     * Registers a new user and issues an API token.
     * Validates the input data, creates the user in the database, and returns an authentication token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(AuthUserRequest $request)
    {
        $data = $request->validate($request->registerRules());

        $user = User::create($data);

        $token = $user->createToken('User-Token' , ['user']);

        return $this->successResponse(
            [
                'user' => $user,
                'token' => $token->plainTextToken,
            ],
            'user registered successfully.'
        );
    }

    /**
     * Retrieves the profile information of the authenticated user user.
     * Requires a valid API token to access user data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->successResponse(auth('user')->user());
    }

    /**
     * Logs out the authenticated user by invalidating their API token.
     * Typically, this involves revoking or deleting the token to prevent further access.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return $this->successResponse(message:'Logged out successfully.');
    }


}
