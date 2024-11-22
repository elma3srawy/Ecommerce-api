<?php

namespace App\Http\Controllers\Authentication\Staff;

use App\Models\Staff;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Authentication\AuthStaffRequest;

class AuthController extends Controller
{
    use ResponseTrait;
    /**
     * Authenticates an staff user and generates an API token.
     * Validates the login credentials, and if successful, returns an authentication token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AuthStaffRequest $request)
    {

        $request->validate($request->loginRules());

        $staff = Staff::where('phone', $request->phone)->first();

        if($staff && Hash::check($request->password , $staff->password))
        {
            $token = $staff->createToken('Staff-Token' , ['Staff']);

            return $this->successResponse(
                [
                    'staff' => $staff,
                    'token' => $token->plainTextToken,
                ],
                'staff login successfully.'
            );
        }
        return $this->errorResponse(message:'Invalid credentials. Please check your phone and password.' , statusCode :401);
    }

    /**
     * Registers a new staff user and issues an API token.
     * Validates the input data, creates the user in the database, and returns an authentication token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(AuthStaffRequest $request)
    {
        $data = $request->validate($request->registerRules());

        $staff = Staff::create($data);

        $token = $staff->createToken('Staff-Token' , ['staff']);

        return $this->successResponse(
            [
                'staff' => $staff,
                'token' => $token->plainTextToken,
            ],
            'staff registered successfully.'
        );
    }

    /**
     * Retrieves the profile information of the authenticated staff user.
     * Requires a valid API token to access user data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->successResponse(auth('staff')->user());
    }

    /**
     * Logs out the authenticated staff user by invalidating their API token.
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
