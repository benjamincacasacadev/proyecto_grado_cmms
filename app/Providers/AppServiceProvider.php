<?php

namespace App\Providers;

use App\Inventory;
use App\InvIncomesDetails;
use App\InvOutcomesDetails;
use App\InvTransfers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        //  Mapeo para polimorfismo
        Relation::morphMap([
            // Inventario
            'A0' => Inventory::class,
            'A1' => InvIncomesDetails::class,
            'A2' => InvOutcomesDetails::class,
            'A3' => InvTransfers::class,
        ]);
    }
}
