<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View ;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $views = [
            'admin.body.header',
        ];

        View::composer($views, function ($view) {
            $id = Auth::id();
            $profileData = User::find($id);
            $view->with(compact($profileData));
        });
        
    }
}
