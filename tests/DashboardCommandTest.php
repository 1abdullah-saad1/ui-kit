<?php

namespace Uom\UiKit\Tests;

use Illuminate\Support\Facades\Artisan;

class DashboardCommandTest extends TestCase
{
    public function test_scaffolds_blade_dashboard(): void
    {
        $status = Artisan::call('uom:dashboard');
        $this->assertSame(0, $status);
        $view = $this->app->basePath('resources/views/uom/dashboard.blade.php');
        $this->assertFileExists($view);
        $this->assertStringContainsString('card-title', file_get_contents($view));
    }

    public function test_scaffolds_livewire_dashboard_when_available(): void
    {
        // Livewire is installed as dev dependency; ensure command works
        $status = Artisan::call('uom:dashboard', ['--livewire' => true]);
        $this->assertSame(0, $status);

        $class = $this->app->basePath('app/Http/Livewire/Uom/Dashboard.php');
        $lwView = $this->app->basePath('resources/views/livewire/uom/dashboard.blade.php');
        $pageView = $this->app->basePath('resources/views/uom/dashboard.blade.php');
        $this->assertFileExists($class);
        $this->assertFileExists($lwView);
        $this->assertFileExists($pageView);
    }
}
