<?php

namespace App\Providers;

use function foo\func;
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
        view()->composer('*', function($view) {

            $allTags = \Cache::rememberForever('tags.list', function() {

                return \App\Tag::all();
            });

            $currentUser = auth()->user();
            //$sortCols = config('project.sorting');
            $view->with(compact('allTags', 'currentUser'));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //

        if($this->app->environment('local')) {

            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
