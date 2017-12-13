<?php

namespace App\Providers;

use App\Comment;
use App\ListFile;
use App\Lists;
use App\Observers\CommentsObserver;
use App\Observers\ListFilesObserver;
use App\Observers\ListsObserver;
use App\Observers\SharedListsObserver;
use App\Observers\UsersObserver;
use App\SharedList;
use App\User;
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
        ListFile::observe(ListFilesObserver::class);
        SharedList::observe(SharedListsObserver::class);
        User::observe(UsersObserver::class);
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
