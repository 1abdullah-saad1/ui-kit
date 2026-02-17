<?php

namespace Uom\UiKit\Console;

use Illuminate\Console\Command;

class UomInstallCommand extends Command
{
    protected $signature = 'uom:install {--dev : Add packages to devDependencies instead of dependencies}';
    protected $description = 'Install Bootstrap via npm and scaffold Vite imports.';

    public function handle(): int
    {
        $packageJsonPath = base_path('package.json');
        if (!file_exists($packageJsonPath)) {
            $this->error('package.json not found. Run this inside a Laravel project root.');
            return self::FAILURE;
        }

        $json = json_decode(file_get_contents($packageJsonPath), true) ?: [];
        $depsKey = $this->option('dev') ? 'devDependencies' : 'dependencies';
        $json[$depsKey] = $json[$depsKey] ?? [];

        $added = [];
        $updated = [];

        $ensure = function (string $pkg, string $version) use (&$json, $depsKey, &$added, &$updated) {
            if (!isset($json[$depsKey][$pkg])) {
                $json[$depsKey][$pkg] = $version;
                $added[] = $pkg;
            } elseif ($json[$depsKey][$pkg] !== $version) {
                $json[$depsKey][$pkg] = $version;
                $updated[] = $pkg;
            }
        };

        $ensure('bootstrap', '^5.3.0');
        $ensure('@popperjs/core', '^2.11.8');
        $ensure('@fortawesome/fontawesome-free', '^6.6.0');

        file_put_contents($packageJsonPath, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL);

        $this->info('package.json updated (' . $depsKey . ')');
        if ($added) { $this->line('  Added: ' . implode(', ', $added)); }
        if ($updated) { $this->line('  Updated: ' . implode(', ', $updated)); }

        $this->scaffoldImports();
        $this->info('Bootstrap ready. Next run: npm install && npm run build');
        return self::SUCCESS;
    }

    private function scaffoldImports(): void
    {
        $jsApp = base_path('resources/js/app.js');
        $cssApp = base_path('resources/css/app.css');
        $sassApp = base_path('resources/sass/app.scss');

        $this->ensureLine($jsApp, "import 'bootstrap';\n");

        if (file_exists($sassApp)) {
            $this->ensureLine($sassApp, '@import "bootstrap/scss/bootstrap";' . PHP_EOL);
            $this->ensureLine($sassApp, '@import "@fortawesome/fontawesome-free/scss/fontawesome";' . PHP_EOL);
            $this->ensureLine($sassApp, '@import "@fortawesome/fontawesome-free/scss/solid";' . PHP_EOL);
            $this->ensureLine($sassApp, '@import "@fortawesome/fontawesome-free/scss/regular";' . PHP_EOL);
            $this->ensureLine($sassApp, '@import "@fortawesome/fontawesome-free/scss/brands";' . PHP_EOL);
        } else {
            $this->ensureLine($cssApp, '@import "bootstrap/dist/css/bootstrap.min.css";' . PHP_EOL);
            $this->ensureLine($cssApp, '@import "@fortawesome/fontawesome-free/css/all.min.css";' . PHP_EOL);
        }
    }

    private function ensureLine(string $path, string $line): void
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $contents = file_exists($path) ? file_get_contents($path) : '';
        if (strpos($contents, trim($line)) === false) {
            $contents = rtrim($contents) . PHP_EOL . $line;
            file_put_contents($path, $contents);
            $this->line('  Updated ' . $this->relPath($path));
        } else {
            $this->line('  Skipped (already contains import): ' . $this->relPath($path));
        }
    }

    private function relPath(string $path): string
    {
        return str_replace(base_path() . DIRECTORY_SEPARATOR, '', $path);
    }
}
