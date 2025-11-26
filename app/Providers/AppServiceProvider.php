<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Squire\Repository as SquireRepository;
use Squire\Models\Country as SquireCountry;
use Squire\Models\Region as SquireRegion;
use App\Models\Country;
use App\Models\Region;

final class AppServiceProvider extends ServiceProvider
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
        foreach (SquireRepository::getSources(SquireCountry::class) as $locale => $path) {
            SquireRepository::registerSource(Country::class, $locale, $path);
        }

        foreach (SquireRepository::getSources(SquireRegion::class) as $locale => $path) {
            SquireRepository::registerSource(Region::class, $locale, $path);
        }
    }
}
