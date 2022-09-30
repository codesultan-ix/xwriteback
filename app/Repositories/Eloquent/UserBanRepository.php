<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\IUser;
use App\Repositories\Contracts\IUserBan;

class UserBanRepository extends BaseRepository implements IUserBan
{
    public function model(){
        return UserBan::class;
    }


}
