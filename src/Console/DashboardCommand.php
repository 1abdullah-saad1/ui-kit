<?php

namespace Uom\UiKit\Console;

use Illuminate\Console\Command;

class DashboardCommand extends Command
{
    protected $signature = 'uom:dashboard {--livewire : Scaffold as a Livewire component}';
    protected $description = 'Generate a Bootstrap-based dashboard page.';

    public function handle(): int
    {
        $isLivewire = (bool) $this->option('livewire');

        if ($isLivewire && !class_exists('Livewire\\Livewire')) {
            $this->warn('Livewire not detected. Install `livewire/livewire` before using --livewire.');
            $isLivewire = false;
        }

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
}
