<?php

namespace App\Providers;

use App\Models\Estacionamento;
use App\Models\Settings;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

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
        config(["adminlte.logo" => 'Sistema de Vendas']);
    }
}
