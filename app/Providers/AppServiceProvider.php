<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
        // Condivisione dati delle categorie nelle viste
        if (Schema::hasTable('categories')) {
            $categories = Category::all();
            View::share(['categories' => $categories]);
        }
        
        // Condivisione dati dei tag nelle viste
        if (Schema::hasTable('tags')) {
            $tags = Tag::all();
            View::share(['tags' => $tags]);
        }

 RateLimiter::for('global', function (Request $request) {
        return Limit::perMinute(150)->by($request->ip());
    });
     
    RateLimiter::for('login', function (Request $request) {
        $email = $request->email;
        Log::info('Login attempt for email: ' . $email . ' from IP: ' . $request->ip());
        return Limit::perMinute(5)->by($email.$request->ip());
    });


        RateLimiter::for('ricerca_articoli', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

       
    }
     }