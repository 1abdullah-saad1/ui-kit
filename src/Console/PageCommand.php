<?php

namespace Uom\UiKit\Console;

use Illuminate\Console\Command;

class PageCommand extends Command
{
    protected $signature = 'uom:page {path : Page path like admin/users} {--r : Add a Route::view entry} {--s : Add sidebar item} {--l : Scaffold as Livewire component}';
    protected $description = 'Generate a Bootstrap page; optionally add route, sidebar, and Livewire component.';

    public function handle(): int
    {
        $pathArg = trim($this->argument('path'), '/');
        $addRoute = (bool) $this->option('r');
        $addSidebar = (bool) $this->option('s');
        $isLivewire = (bool) $this->option('l');

        if ($isLivewire && !class_exists('Livewire\\Livewire')) {
            $this->warn('Livewire not detected. Install `livewire/livewire` before using -l.');
            $isLivewire = false;
        }

        $viewDot = 'uom.' . str_replace('/', '.', $pathArg);
        $viewFile = base_path('resources/views/' . str_replace('.', '/', $viewDot) . '.blade.php');

        if ($isLivewire) {
            $className = $this->studlyPath($pathArg);
            $classPath = base_path('app/Http/Livewire/Uom/' . $className . '.php');
            $lwView = base_path('resources/views/livewire/uom/' . $pathArg . '.blade.php');

            $this->writeFile($classPath, $this->livewireClassStub($className, 'livewire.uom.' . $pathArg));
            $this->writeFile($lwView, $this->livewireViewStub($className));

            $this->writeFile($viewFile, "@extends('layouts.app')\n\n@section('content')\n<div class=\"container py-4\">\n    @livewire('uom." . $this->kebabPath($pathArg) . "')\n</div>\n@endsection\n");
        } else {
            $this->writeFile($viewFile, $this->bladePageStub($pathArg));
        }

        if ($addRoute) {
            $routePath = '/' . $pathArg;
            $routeName = str_replace('/', '.', 'uom.' . $pathArg);
            $this->appendRouteView(base_path('routes/web.php'), $routePath, $viewDot, $routeName);
        }

        if ($addSidebar) {
            $sidebar = base_path('resources/views/partials/uom-sidebar.blade.php');
            $label = ucfirst(basename($pathArg));
            $routeName = str_replace('/', '.', 'uom.' . $pathArg);
            $this->appendSidebarItem($sidebar, $routeName, $label);
            $this->info('Sidebar updated. Include partial: @include("partials.uom-sidebar") in your layout.');
        }

        $this->info('Page generated: ' . $viewDot);
        return self::SUCCESS;
    }

    private function writeFile(string $path, string $contents): void
    {
        $dir = dirname($path);
        if (!is_dir($dir)) { mkdir($dir, 0755, true); }
        file_put_contents($path, $contents);
        $this->line('  Wrote ' . $this->relPath($path));
    }

    private function relPath(string $path): string
    {
        return str_replace(base_path() . DIRECTORY_SEPARATOR, '', $path);
    }

    private function bladePageStub(string $path): string
    {
        $title = ucfirst(str_replace('/', ' / ', $path));
        return <<<BLADE
@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4">{$title}</h1>
  </div>
  <div class="card shadow-sm">
    <div class="card-body">
      <p class="text-muted mb-0">Your content goes here.</p>
    </div>
  </div>
</div>
@endsection
BLADE;
    }

    private function livewireClassStub(string $className, string $view): string
    {
        return <<<PHP
<?php

namespace App\Http\Livewire\Uom;

use Livewire\Component;

class {$className} extends Component
{
    public function render()
    {
        return view('{$view}');
    }
}
PHP;
    }

    private function livewireViewStub(string $title): string
    {
        return <<<BLADE
<div>
  <div class="card shadow-sm">
    <div class="card-body">
      <h5 class="card-title">{$title} (Livewire)</h5>
      <p class="card-text text-muted">Interactive content.</p>
    </div>
  </div>
</div>
BLADE;
    }

    private function appendRouteView(string $webPhp, string $uri, string $viewDot, string $routeName): void
    {
        $line = "Route::view('{$uri}', '{$viewDot}')->name('{$routeName}');";
        $contents = file_exists($webPhp) ? file_get_contents($webPhp) : "<?php\n\n";
        if (strpos($contents, $line) === false) {
            $contents = rtrim($contents) . PHP_EOL . $line . PHP_EOL;
            file_put_contents($webPhp, $contents);
            $this->line('  Appended route in ' . $this->relPath($webPhp));
        } else {
            $this->line('  Skipped (route exists): ' . $uri);
        }
    }

    private function appendSidebarItem(string $sidebarPath, string $routeName, string $label): void
    {
        $dir = dirname($sidebarPath);
        if (!is_dir($dir)) { mkdir($dir, 0755, true); }
        $contents = file_exists($sidebarPath) ? file_get_contents($sidebarPath) : "<ul class=\"list-unstyled\"></ul>\n";
        $item = "<li><a class=\"link-dark\" href=\"{{ route('{$routeName}') }}\">{$label}</a></li>\n";
        if (strpos($contents, $item) === false) {
            $contents = preg_replace('/<\\/ul>/', $item . '</ul>', $contents, 1) ?: ($contents . $item);
            file_put_contents($sidebarPath, $contents);
            $this->line('  Appended sidebar item in ' . $this->relPath($sidebarPath));
        } else {
            $this->line('  Skipped (sidebar item exists): ' . $label);
        }
    }

    private function studlyPath(string $path): string
    {
        $parts = array_map(function ($p) {
            return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $p)));
        }, explode('/', $path));
        return implode('', $parts);
    }

    private function kebabPath(string $path): string
    {
        return strtolower(str_replace(['/', '_'], ['.', '-'], $path));
    }
}
