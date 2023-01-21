<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use URL;
class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //if($this->app->environment('production')) {
    		URL::forceScheme('https');
	//}
    }
    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Only load LaravelIdeHelper if we're in development mode
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
