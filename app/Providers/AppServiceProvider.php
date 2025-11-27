<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Country;
use App\Models\Region;
use Illuminate\Support\ServiceProvider;
use Squire\Models\Country as SquireCountry;
use Squire\Models\Region as SquireRegion;
use Squire\Repository as SquireRepository;

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
        /** @phpstan-ignore foreach.nonIterable */
        foreach (SquireRepository::getSources(SquireCountry::class) as $locale => $path) {
            /** @phpstan-ignore argument.type */
            SquireRepository::registerSource(Country::class, $locale, $path);
        }

        /** @phpstan-ignore foreach.nonIterable */
        foreach (SquireRepository::getSources(SquireRegion::class) as $locale => $path) {
            /** @phpstan-ignore argument.type */
            SquireRepository::registerSource(Region::class, $locale, $path);
        }
    }
}
