<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;

class ComposerServiceProvider extends ServiceProvider
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
        
        // Index Composer
        View::composer(
            [
                'backend.role.index',
                'backend.menu.index',
                'backend.user.index',
                'backend.company.index',


                'backend.customer.index',
                'backend.bill.index',
            ],
            'App\Composers\IndexViewComposer'
        );

    }
}
