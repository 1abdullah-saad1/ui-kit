<?php

namespace Uom\UiKit\View\Components;

use Illuminate\View\Component;

class App extends Component
{
    public string $lang;
    public string $dir;
    public string $title;

    public function __construct(string $lang = 'en', string $dir = 'ltr', string $title = 'UOM App')
    {
        $this->lang = $lang;
        $this->dir = $dir;
        $this->title = $title;
    }

    public function render()
    {
        return view('uom::components.uom.app');
    }
}
