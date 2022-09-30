<?php

namespace App\Http\Controllers\Auth;

use App\Actions\GenerateAPIToken;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    //
    private $data;

    public function username(){
        return filter_var(request('email'),FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    }


    protected function validateLogin(Request $request){

        if (!$request->has('token')){
                 $this->data = $request->validate([
                    $this->username() => 'required|string',
                    'password' => 'required|string',
                ]);
        }
        return $this->data;
    }

    public function attemptLogin(Request $request){

        try{
            $data = $this->validateLogin($request);

            if(!Auth::attempt($request->only([$this->username(), 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }
            $user = User::where($this->username(), $request->{$this->username()})->first();
            Log::info("User Logged In Successfully {$user->id}");
            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => GenerateAPIToken::run($user,$data,$this->abilities())
            ], 200);


        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
            // $user = User::where($this->username(), $request->email)->first();
            // dd($user);
            // $user = $this->guard()
    private function abilities(): array
    {
        return [
            'access-user-api',
        ];
    }

}
