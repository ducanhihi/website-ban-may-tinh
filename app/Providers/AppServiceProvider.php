<?php

namespace App\Providers;

use App\Models\CartDetail;
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
    public function boot()
    {
        View::composer('*', function ($view) {
            $cartCount = 0;
            if (auth()->check()) {
                $cart = auth()->user()->cart;
                if ($cart) {
                    $cartCount = CartDetail::where('cart_id', $cart->id)->sum('quantity');
                }
            }
            $view->with('cartCount', $cartCount);
        });
    }
}
