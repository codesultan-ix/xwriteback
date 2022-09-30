<?php

namespace App\Providers;

use App\Modules\Admin\Models\User;
use Illuminate\Support\ServiceProvider;


class RelationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Relation::morphMap([
        //     'lead' => Lead::class,
        //     'candidate' => Candidate::class,
        //     'partner' => Partner::class,
        //     'admin' => User::class,
        //     'account' => Account::class,
        //     'loyalty_activity' => LoyaltyActivity::class,
        //     'loyalty_reward' => LoyaltyReward::class,
        //     'loyalty_claim' => LoyaltyClaim::class,
        //     'recipe' => Recipe::class,
        // ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
