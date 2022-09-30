<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\Contracts\IUser;
use Illuminate\Foundation\Auth\RegistersUsers;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    //
    use RegistersUsers;
    protected $users;

    public function __construct(IUser $user)
    {
        $this->users = $user;
    }

    protected function registered(Request $request, User $user ){
        return response()->json($user ,200);
    }
    public function validator(array $data){
        return Validator::make($data,[
            'name' => ['required','string','max:255'],
            'username' => ['required','string','max:15','alpha_dash','unique:users,username'],
            'email' => ['required','string','email', 'max:255','unique:users'],
            'password' => ['required','string','min:8','confirmed'],

        ]);

    }

    public function create(array $data)
    // public function register(Request $request)
    {
        // dd(geoip()->getLocation($_SERVER['REMOTE_ADDR']));
        // $data = $request->validate([
        //             'name' => ['required','string','max:255'],
        //             'username' => ['required','string','max:15','alpha_dash','unique:users,username'],
        //             'email' => ['required','string','email', 'max:255','unique:users'],
        //             'password' => ['required','string','min:8','confirmed'],

        //         ]);
        $arr_ip = geoip()->getLocation($_SERVER['REMOTE_ADDR']);
        $location = new Point($arr_ip['lat'],$arr_ip['lon']);

        return $this->users->create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
            '$location' => $location
        ]);

    }
}
