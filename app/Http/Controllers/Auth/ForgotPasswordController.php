<?php

namespace App\Http\Controllers\Auth;

use App\Actions\GenerateOTP;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;
    //
    public function sendResetLinkResponse(Request $request, $response){
        return response()->json(['status' => trans($response)], 200);
    }

    public function sendResetlinkFailedResponse(Request $request, $response){
        return response()->json(['email' => trans($response)], 422);
    }



}
