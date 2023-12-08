<?php

namespace DbBackup;

use Illuminate\Support\ServiceProvider;

class BackupServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('dbbackup', function ($app) {
            return new BackupManager();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/dbbackup.php' => config_path('dbbackup.php'),
        ], 'config');
    }
}
