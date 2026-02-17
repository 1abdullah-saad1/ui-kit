<?php

namespace Uom\UiKit\View\Components;

use Illuminate\View\Component;

class Sidebar extends Component
{
    public string $anchor;
    public ?string $title;

    public function __construct(string $anchor = 'left', ?string $title = null)
    {
        $this->anchor = in_array($anchor, ['left', 'right']) ? $anchor : 'left';
        $this->title = $title;
    }

    public function render()
    {
        return view('uom::components.uom.sidebar');
    }
}