<?php

namespace Uom\UiKit\Console;

use Illuminate\Console\Command;

class DashboardCommand extends Command
{
  protected $signature = 'uom:dashboard {--livewire : Scaffold as a Livewire component}';
  protected $description = 'Generate a Bootstrap-based dashboard page.';

  /**
   * Yields required in the base layout to render generated pages
   * @var array<string>
   */
  protected array $requiredYields = ['content'];

    public function handle(): int
    {
        $isLivewire = (bool) $this->option('livewire');

        if ($isLivewire && !class_exists('Livewire\\Livewire')) {
            $this->warn('Livewire not detected. Install `livewire/livewire` before using --livewire.');
            $isLivewire = false;
        }

        // Ensure base layout exists and is valid
        $layout = base_path('resources/views/layouts/app.blade.php');
        $this->ensureLayout($layout);

        if ($isLivewire) {
            $class = base_path('app/Http/Livewire/Uom/Dashboard.php');
            $view = base_path('resources/views/livewire/uom/dashboard.blade.php');
            $this->writeFile($class, $this->livewireClassStub('Uom\\Dashboard'));
            $this->writeFile($view, $this->livewireViewStub('Dashboard'));
            $pageView = base_path('resources/views/uom/dashboard.blade.php');
            $this->writeFile($pageView, "@extends('layouts.app')\n\n@section('content')\n<div class=\"container py-4\">\n    @livewire('uom.dashboard')\n</div>\n@endsection\n");
            $this->info('Livewire dashboard generated.');
        } else {
            $view = base_path('resources/views/uom/dashboard.blade.php');
            $this->writeFile($view, $this->bladeDashboardStub());
            $this->info('Blade dashboard generated.');
        }

        return self::SUCCESS;
    }

      private function ensureLayout(string $layoutPath): void
      {
        $exists = file_exists($layoutPath);
        if ($exists) {
          // Ask developer whether to replace existing layout
          if ($this->confirm('A layout already exists at layouts/app.blade.php. Replace with UI Kit default?', false)) {
            $this->writeFile($layoutPath, $this->defaultLayoutStub());
            $this->info('Replaced base layout with UI Kit default.');
            return;
          }

          // Validate required yields
          $contents = file_get_contents($layoutPath) ?: '';
          $missing = [];
          foreach ($this->requiredYields as $yield) {
            $pattern = '/@yield\(\s*[\'\"]' . preg_quote($yield, '/') . '[\'\"]\s*\)/';
            if (!preg_match($pattern, $contents)) {
              $missing[] = $yield;
            }
          }
          if ($missing) {
            throw new \RuntimeException('Layout is missing required @yield sections: ' . implode(', ', $missing) . '. Aborting dashboard scaffolding.');
          }
        } else {
          // Create default layout if none exists
          $this->writeFile($layoutPath, $this->defaultLayoutStub());
          $this->info('Created base layout at resources/views/layouts/app.blade.php');
        }
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

    private function bladeDashboardStub(): string
    {
        return <<<'BLADE'
@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="row g-3">
    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Users</h5>
          <p class="card-text">Manage users and roles.</p>
          <a href="#" class="btn btn-primary">Open</a>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Reports</h5>
          <p class="card-text">View system reports.</p>
          <a href="#" class="btn btn-outline-primary">Open</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
BLADE;
    }

    private function livewireClassStub(string $component): string
    {
        return <<<'PHP'
<?php

namespace App\Http\Livewire\Uom;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.uom.dashboard');
    }
}
PHP;
    }

    private function livewireViewStub(string $title): string
    {
        return <<<'BLADE'
<div>
  <div class="row g-3">
    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Livewire Dashboard</h5>
          <p class="card-text">Interactive widgets go here.</p>
        </div>
      </div>
    </div>
  </div>
</div>
BLADE;
    }

  private function defaultLayoutStub(): string
  {
    return <<<'BLADE'
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'UOM App')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light mb-3">
  <div class="container">
    <a class="navbar-brand" href="#">UOM</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
      </ul>
    </div>
  </div>
</nav>

<main>
  @yield('content')
</main>

</body>
</html>
BLADE;
  }
}
