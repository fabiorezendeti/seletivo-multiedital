<?php

namespace App\Providers;

use App\Services\CsvLib\CsvFileService;
use App\Services\CsvLib\Interfaces\CsvWriter;
use Illuminate\Support\ServiceProvider;

class CsvFileProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            CsvWriter::class,
            CsvFileService::class            
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
