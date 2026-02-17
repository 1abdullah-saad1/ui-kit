<?php

namespace Uom\UiKit;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Uom\UiKit\Console\UomInstallCommand;
use Uom\UiKit\Console\DashboardCommand;
use Uom\UiKit\Console\PageCommand;
use Uom\UiKit\View\Components\App;
use Uom\UiKit\View\Components\TopNav;
use Uom\UiKit\View\Components\Sidebar;
use Uom\UiKit\View\Components\SidebarItem;
use Uom\UiKit\View\Components\SidebarItemDropdown;
use Uom\UiKit\View\Components\UserDropdown;

class UiKitServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register any bindings if needed in the future
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'uom');

        // Register Blade components with dot aliases (e.g. <x-uom.app>)
        Blade::component(App::class, 'uom.app');
        Blade::component(TopNav::class, 'uom.top-nav');
        Blade::component(Sidebar::class, 'uom.sidebar');
        Blade::component(SidebarItem::class, 'uom.sidebar-item');
        Blade::component(SidebarItemDropdown::class, 'uom.sidebar-item-dropdown');
        Blade::component(UserDropdown::class, 'uom.user-dropdown');

        if ($this->app->runningInConsole()) {
            $this->commands([
                UomInstallCommand::class,
                DashboardCommand::class,
                PageCommand::class,
            ]);
        }
    }
}