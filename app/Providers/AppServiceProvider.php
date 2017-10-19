<?php

namespace App\Providers;

use App\Comment;
use App\Lists;
use App\Observers\CommentsObserver;
use App\Observers\ListsObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Lists::observe(ListsObserver::class);
        Comment::observe(CommentsObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
