<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\SocialLoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => ['guest:api']], function () {
    Route::post('/login', [LoginController::class, 'attemptLogin'])->name('login');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.reset');
    Route::post('/send-password-reset-email-otp', [PasswordResetController::class, 'sendResetOtp'])->name('password.reset.otp');
    Route::post('/verify-password-reset-otp', [PasswordResetController::class, 'verifyOtp'])->name('password.verify.otp');
    Route::post('register', [RegistrationController::class, 'register'])->name('user.register');
});
Route::group([['middleware' => 'throttle:20,5']], function () {
    Route::get('/login/{provider}', [SocialLoginController::class,'redirectToProvider']);
    Route::get('/login/{provider}/callback', [SocialLoginController::class, 'handleProviderCallback']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
