<?php

namespace Uom\UiKit\View\Components;

use Illuminate\View\Component;

class TopNav extends Component
{
    public string $brand;
    public ?string $toggleTarget;

    public function __construct(string $brand = 'UOM', ?string $toggleTarget = null)
    {
        $this->brand = $brand;
        $this->toggleTarget = $toggleTarget;
    }

    public function render()
    {
        return view('uom::components.uom.top-nav');
    }
}
