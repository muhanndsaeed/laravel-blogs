<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {   //addDays() or addHours() or addMinutes()
        Passport::personalAccessTokensExpireIn(Carbon::now()->addDays(1));
    }
}
