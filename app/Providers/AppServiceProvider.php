<?php

namespace App\Providers;

use App\Support\Site;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(Site::class);
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('site', app(Site::class));
        });

        // Error pages render outside the web middleware group, so nothing has
        // shared an error bag yet and any @error directive would fatal. The
        // session middleware overwrites this on normal requests.
        View::share('errors', new ViewErrorBag);
    }
}
