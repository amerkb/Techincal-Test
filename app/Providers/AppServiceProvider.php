<?php

namespace App\Providers;

use App\Interfaces\Admin\ProductInterface;
use App\Interfaces\AuthInterface;
use App\Interfaces\user\OrderInterface;
use App\Repository\Admin\ProductRepository;
use App\Repository\AuthRepository;
use App\Repository\User\OrderRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AuthInterface::class, function () {
            return new AuthRepository;
        });

        $this->app->bind(OrderInterface::class, function () {
            return new OrderRepository;
        });

        $this->app->bind(ProductInterface::class, function () {
            return new ProductRepository;
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
