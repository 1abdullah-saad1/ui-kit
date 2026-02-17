<?php

namespace Uom\UiKit;

use Illuminate\Support\ServiceProvider;
use Uom\UiKit\Console\UomInstallCommand;
use Uom\UiKit\Console\DashboardCommand;
use Uom\UiKit\Console\PageCommand;

class UiKitServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register any bindings if needed in the future
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                UomInstallCommand::class,
                DashboardCommand::class,
                PageCommand::class,
            ]);
        }
    }
}