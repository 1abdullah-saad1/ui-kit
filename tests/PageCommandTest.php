<?php

namespace Uom\UiKit\Tests;

use Illuminate\Support\Facades\Artisan;

class PageCommandTest extends TestCase
{
    public function test_scaffolds_page_with_route_and_sidebar(): void
    {
        $status = Artisan::call('uom:page', [
            'path' => 'admin/users',
            '--r' => true,
            '--s' => true,
        ]);
        $this->assertSame(0, $status);

        $view = $this->app->basePath('resources/views/uom/admin/users.blade.php');
        $this->assertFileExists($view);
        $this->assertStringContainsString('Your content goes here', file_get_contents($view));

        $web = $this->app->basePath('routes/web.php');
        $this->assertStringContainsString("Route::view('/admin/users', 'uom.admin.users')", file_get_contents($web));

        $sidebar = $this->app->basePath('resources/views/partials/uom-sidebar.blade.php');
        $this->assertFileExists($sidebar);
        $this->assertStringContainsString("route('uom.admin.users')", file_get_contents($sidebar));
    }

    public function test_scaffolds_livewire_page_when_flagged(): void
    {
        $status = Artisan::call('uom:page', [
            'path' => 'reports/monthly',
            '--l' => true,
        ]);
        $this->assertSame(0, $status);

        $class = $this->app->basePath('app/Http/Livewire/Uom/ReportsMonthly.php');
        $lwView = $this->app->basePath('resources/views/livewire/uom/reports/monthly.blade.php');
        $pageView = $this->app->basePath('resources/views/uom/reports/monthly.blade.php');
        $this->assertFileExists($class);
        $this->assertFileExists($lwView);
        $this->assertFileExists($pageView);
    }
}
