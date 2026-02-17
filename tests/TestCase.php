<?php

namespace Uom\UiKit\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Uom\UiKit\UiKitServiceProvider;

class TestCase extends BaseTestCase
{
    protected string $tmpBasePath;

    protected function getPackageProviders($app)
    {
        return [UiKitServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->tmpBasePath = sys_get_temp_dir() . '/uom-ui-kit-' . bin2hex(random_bytes(4));
        $this->prepareBasePath($this->tmpBasePath);
        $this->app->setBasePath($this->tmpBasePath);
    }

    protected function tearDown(): void
    {
        $this->deleteDir($this->tmpBasePath);
        parent::tearDown();
    }

    private function prepareBasePath(string $path): void
    {
        @mkdir($path, 0755, true);
        @mkdir($path . '/resources/js', 0755, true);
        @mkdir($path . '/resources/css', 0755, true);
        @mkdir($path . '/resources/sass', 0755, true);
        @mkdir($path . '/resources/views', 0755, true);
        @mkdir($path . '/routes', 0755, true);
        file_put_contents($path . '/routes/web.php', "<?php\n\n");
        file_put_contents($path . '/package.json', json_encode(['name' => 'test-app'], JSON_PRETTY_PRINT));
    }

    private function deleteDir(string $dir): void
    {
        if (!is_dir($dir)) return;
        $items = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($items as $item) {
            $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
        }
        rmdir($dir);
    }
}
