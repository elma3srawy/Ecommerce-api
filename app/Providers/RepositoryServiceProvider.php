<?php

namespace App\Providers;

use App\Repository\OrderRepository;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Illuminate\Support\ServiceProvider;
use App\Repository\CouponUserRepository;
use App\Interfaces\Repository\OrderRepositoryInterface;
use App\Interfaces\Repository\CouponRepositoryInterface;
use App\Interfaces\Repository\ProductRepositoryInterface;
use App\Interfaces\Repository\CategoryRepositoryInterface;
use App\Interfaces\Repository\CouponUserRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CouponRepositoryInterface::class, CouponRepository::class);
        $this->app->bind(CouponUserRepositoryInterface::class, CouponUserRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
