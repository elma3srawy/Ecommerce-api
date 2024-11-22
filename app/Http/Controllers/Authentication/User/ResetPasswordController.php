<?php

namespace App\Http\Controllers\Authentication\User;


use App\Models\User;
use App\Models\Verification;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPassword\UserResetPasswordRequest;


class ResetPasswordController extends Controller
{
    use ResponseTrait;

    public function forgetPassword(UserResetPasswordRequest $request)
    {
        if (!$user = User::where('phone' , '=',$request->phone)->first())
        {
            return $this->errorResponse(message: 'User Not Found' , statusCode: 404);
        }

        $user->sendMobileSmsCodeToResetPassword();

        return $this->successResponse(message: 'Password reset code sent via SMS.');
    }

    public function resetPassword(UserResetPasswordRequest $request)
    {
        try{
            DB::beginTransaction();

            $verification = Verification::where('token', '=', $request->token)->first();

            if (!$verification || now()->greaterThan($verification->expires_at)) {
                return $this->errorResponse(message:'Invalid or expired token.' , statusCode:400);
            }

            $user = $verification->tokenable;

            if (!$user) {
                return $this->errorResponse(message:'User not found.' , statusCode:404);
            }

            $user->password = bcrypt($request->password);

            $user->save();

            $user->tokens()->delete();

            $verification->delete();

            DB::commit();

            return response()->json(['message' => 'Password has been successfully reset.'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(message: 'An error occurred while resetting the password.', statusCode: 500);
        }
    }
}
