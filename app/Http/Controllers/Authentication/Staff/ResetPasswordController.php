<?php

namespace App\Http\Controllers\Authentication\Staff;


use App\Models\Staff;
use App\Models\Verification;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPassword\StaffResetPasswordRequest;


class ResetPasswordController extends Controller
{
    use ResponseTrait;

    public function forgetPassword(StaffResetPasswordRequest $request)
    {
        if (!$staff = Staff::where('phone' , '=',$request->phone)->first())
        {
            return $this->errorResponse(message: 'Staff Not Found' , statusCode: 404);
        }

        $staff->sendMobileSmsCodeToResetPassword();

        return $this->successResponse(message: 'Password reset code sent via SMS.');
    }

    public function resetPassword(StaffResetPasswordRequest $request)
    {
        try{
            DB::beginTransaction();

            $verification = Verification::where('token', '=', $request->token)->first();

            if (!$verification || now()->greaterThan($verification->expires_at)) {
                return $this->errorResponse(message:'Invalid or expired token.' , statusCode:400);
            }

            $staff = $verification->tokenable;

            if (!$staff) {
                return $this->errorResponse(message:'Staff not found.' , statusCode:404);
            }

            $staff->password = bcrypt($request->password);

            $staff->save();

            $staff->tokens()->delete();

            $verification->delete();

            DB::commit();

            return response()->json(['message' => 'Password has been successfully reset.'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(message: 'An error occurred while resetting the password.', statusCode: 500);
        }
    }
}
