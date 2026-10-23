<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    Gate::define('manage-seller-requests', function (User $user): bool {

        return $user->role === 'admin'
            || $user->role === 'super_admin';

    });


    Gate::define('create-store',function(User $user):bool{

        return $user->role ==='seller' && !$user->store()->exists();
    });

    Gate::define('manage-store',function(User $user){
     
       return $user->role === 'seller' && $user->store()->exists();


    });


Gate::define('manage-products', function (User $user) {
    return in_array($user->role, ['admin', 'super_admin'], true);
});

Gate::define('view-admin-dashboard', function (User $user) {
    return in_array($user->role, ['admin', 'super_admin'], true);
});
}
}
