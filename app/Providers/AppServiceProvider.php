<?php

namespace App\Providers;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
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
        Model::preventLazyLoading();

        View::composer('components.header', function ($view) {
            if(!Auth::check()) return;

            $service = app(NotificationService::class);

            $data = $service->getNotifications(Auth::user(), 15);

            $view->with($data);
        });

        RateLimiter::for('make-post', function(HttpRequest $request) {
            return Limit::perSecond(1, 5)->by($request->user()->id)
                ->response(function ($request, $headers) {
                    $retryAfter = $headers['Retry-After'] ?? 5;
                    return back()
                        ->withErrors(['content' => "You must wait {$retryAfter} seconds before posting again."])
                        ->withInput();
                });
        });

        RateLimiter::for('make-thread', function(HttpRequest $request) {
            return Limit::perSecond(1, 5)->by($request->user()->id)
                ->response(function ($request, $headers) {
                    $retryAfter = $headers['Retry-After'] ?? 1;
                    return back()
                        ->withErrors(['content' => "You must wait {$retryAfter} seconds before creating a thread again."])
                        ->withInput();
                });
        });

        RateLimiter::for('avatar-update', function(HttpRequest $request) {
            return Limit::perMinute(5, 1)->by($request->user()->id)
                ->response(function ($request, $headers) {
                    $retryAfter = $headers['Retry-After'] ?? 1;
                    return back()
                        ->withErrors(['avatar' => "You must wait {$retryAfter} seconds before updating your avatar again."])
                        ->withInput();
                });
        });

        RateLimiter::for('login', function(HttpRequest $request) {
            return Limit::perMinute(5, 1)->by($request->ip())
                ->response(function ($request, $headers) {
                    $retryAfter = $headers['Retry-After'] ?? 1;
                    return back()
                        ->withErrors(['spam' => "You must wait {$retryAfter} seconds before trying to login again."])
                        ->withInput();
                });
        });

        RateLimiter::for('register', function(HttpRequest $request) {
            return Limit::perMinute(5, 1)->by($request->ip())
                ->response(function ($request, $headers) {
                    $retryAfter = $headers['Retry-After'] ?? 1;
                    return back()
                        ->withErrors(['spam' => "You must wait {$retryAfter} seconds before trying to register again."])
                        ->withInput();
                });
        });

        RateLimiter::for('conv-invite', function(HttpRequest $request) {
            return Limit::perSecond(1, 5)->by($request->user()->id)
                ->response(function($headers) {
                    $retryAfter = $headers['Retry-After'] ?? 1;
                    return back()
                        ->withErrors(['search' => "You must wait {$retryAfter} seconds before trying to invite again."])
                        ->withInput();
                });
        });

        Gate::define('follow-user', function (User $user, $profileUser) {
            return $user->id !== $profileUser->id;
        });

    }
}
