<?php

namespace App\Http\Controllers\Authentication\Admin;


use App\Models\Admin;
use App\Models\Verification;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPassword\AdminResetPasswordRequest;



class ResetPasswordController extends Controller
{
    use ResponseTrait;

    public function forgetPassword(AdminResetPasswordRequest $request)
    {
        if (!$admin = Admin::where('email' , '=',$request->email)->first())
        {
            return $this->errorResponse(message: 'Admin Not Found' , statusCode: 404);
        }

        $admin->sendEmailToResetPasswordWithToken();

        return $this->successResponse(message: 'Password reset link sent to your email.');
    }

    public function resetPassword(AdminResetPasswordRequest $request)
    {
        try{
            DB::beginTransaction();

            $verification = Verification::where('token', '=', $request->token)->first();

            if (!$verification || now()->greaterThan($verification->expires_at)) {
                return $this->errorResponse(message:'Invalid or expired token.' , statusCode:400);
            }

            $admin = $verification->tokenable;

            if (!$admin) {
                return $this->errorResponse(message:'Admin not found.' , statusCode:404);
            }

            $admin->password = bcrypt($request->password);

            $admin->save();

            $admin->tokens()->delete();

            $verification->delete();

            DB::commit();

            return response()->json(['message' => 'Password has been successfully reset.'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(message: 'An error occurred while resetting the password.', statusCode: 500);
        }
    }
}
