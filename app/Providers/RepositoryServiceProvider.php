<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\{
    IBase,
    IUser,
    IUserBan
};

use App\Repositories\Eloquent\{
    UserRepository,
    UserBanRepository
};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->app->bind(IUser::class , UserRepository::class);
        $this->app->bind(IUserBan::class , UserBanRepository::class);
    }
}
