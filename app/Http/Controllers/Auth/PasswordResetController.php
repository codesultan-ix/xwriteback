<?php

namespace App\Http\Controllers\Auth;

use App\Actions\GenerateOTP;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\PasswordResetWithOTPNotification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    use ResetsPasswords;
    //
    public function sendResetResponse(Request $request, $response){
        return response()->json(['status' => trans($response)], 200);
    }

    public function sendResetFailedResponse(Request $request, $response){
        return response()->json(['email' => trans($response)], 422);
    }

    public function sendResetOtp(Request $request){

        try{
            $data = $request->validate([
                'email' => ['required', 'email'],
            ]);

            if (! User::where('email', $data['email'])->exists()) {
                return response()->json([
                    'message' => 'error',
                    'errors' => [
                        'email' => 'Account does not exist!',
                        'code' => 'UNF', //user not found
                    ],
                ], 422);
            }

            $user = User::where('email', $data['email'])->first();
            $data = $request->validate([
                'email' => ['required', 'email'],
            ]);

            if($user->otp?->is_expired){
                $user->otp?->delete();
                $otp = GenerateOTP::run($user);
                $user->notify(new PasswordResetWithOTPNotification($user, $otp));
            }elseif(!$user->otp?->is_expired){
                $user->notify(new PasswordResetWithOTPNotification($user, $user->otp->code));
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }


        return response()->json([
            'data' => [
                'otp_expiry' => $user->load('otp')->otp->expires_at
            ],

            'meta' => [
                'status' => 'success',
                'message' => 'Password reset email sent. Token expires in 5 minutes.',
            ]
        ],200);


    }

    public function verifyOtp(Request $request){



        try{

            $data = $request->validate([
                'otp' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8|confirmed',
            ]);

            if (! User::where('email', $data['email'])->exists()) {
                return response()->json([
                    'message' => 'error',
                    'errors' => [
                        'email' => 'Account does not exist!',
                        'code' => 'UNF', //user not found
                    ],
                ], 422);
            }

            $user = User::where('email', $data['email'])->firstOrFail();
            if (! $user->otp || $user->otp->code !== $request->otp || $user->otp->is_expired) {
                return response()->json([
                    'message' => 'error',
                    'errors' => [
                        'otp' => ['Invalid otp'],
                    ],
                ], 422);
            }

            $user->forceFill([
                'password' => Hash::make($data['password']),
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));

            $user->otp?->delete();
            Log::info("Password reset successfully for {$user->id}");

            return response()->json([
                'data' => null,
                'meta' => [
                    'status' => 'success',
                    'message' => 'Password reset successfully.',
                ]
            ]);
            // $data = $request->validate([
            //     'email' => ['required', 'email'],
            // ]);
            // $user = User::where('email', $data['email'])->first();
            // if ($user->otp){

            // }

        }catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }


        // $user->otp()->delete();
        // dd($user->otp);
        // dd($user->load('otp')->otp);
        // $checkifOtpExists = $user->otp();
    }

}
