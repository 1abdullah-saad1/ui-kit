<?php

namespace Uom\UiKit\Tests;

use Illuminate\Support\Facades\Artisan;

class UomInstallCommandTest extends TestCase
{
    public function test_installs_bootstrap_and_scaffolds_imports(): void
    {
        $status = Artisan::call('uom:install');
        $this->assertSame(0, $status);

        $packageJson = $this->app->basePath('package.json');
        $data = json_decode(file_get_contents($packageJson), true);
        $this->assertArrayHasKey('dependencies', $data);
        $this->assertArrayHasKey('bootstrap', $data['dependencies']);
        $this->assertArrayHasKey('@popperjs/core', $data['dependencies']);

        $jsApp = $this->app->basePath('resources/js/app.js');
        $this->assertStringContainsString("import 'bootstrap';", file_get_contents($jsApp));

        $cssApp = $this->app->basePath('resources/css/app.css');
        $sassApp = $this->app->basePath('resources/sass/app.scss');
        $css = file_exists($cssApp) ? file_get_contents($cssApp) : '';
        $sass = file_exists($sassApp) ? file_get_contents($sassApp) : '';
        $this->assertTrue(str_contains($css, 'bootstrap/dist/css/bootstrap.min.css') || str_contains($sass, 'bootstrap/scss/bootstrap'));
    }
}
