<?php

namespace App\Http\Controllers\Authentication\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Authentication\AuthAdminRequest;

class AuthController extends Controller
{
    use ResponseTrait;
    /**
     * Authenticates an admin user and generates an API token.
     * Validates the login credentials, and if successful, returns an authentication token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AuthAdminRequest $request)
    {
        $request->validate($request->loginRules());

        $admin = Admin::where('email', $request->email)->first();

        if($admin && Hash::check($request->password , $admin->password))
        {
            $token = $admin->createToken('Admin-Token' , ['admin']);

            return $this->successResponse(
                [
                    'admin' => $admin,
                    'token' => $token->plainTextToken,
                ],
                'Admin login successfully.'
            );
        }
        return $this->errorResponse(message:'Invalid credentials. Please check your email and password.' , statusCode :401);
    }

    /**
     * Registers a new admin user and issues an API token.
     * Validates the input data, creates the user in the database, and returns an authentication token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(AuthAdminRequest $request)
    {
        $data = $request->validate($request->registerRules());

        $admin = Admin::create($data);

        $token = $admin->createToken('Admin-Token' , ['admin']);

        return $this->successResponse(
            [
                'admin' => $admin,
                'token' => $token->plainTextToken,
            ],
            'Admin registered successfully.'
        );
    }

    /**
     * Retrieves the profile information of the authenticated admin user.
     * Requires a valid API token to access user data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->successResponse(auth('admin')->user());
    }

    /**
     * Logs out the authenticated admin user by invalidating their API token.
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
