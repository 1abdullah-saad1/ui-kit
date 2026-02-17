<?php

namespace Uom\UiKit\View\Components;

use Illuminate\View\Component;

class SidebarItemDropdown extends Component
{
    public string $title;
    public ?string $icon;
    public string $id;

    public function __construct(string $title, ?string $icon = null, ?string $id = null)
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->id = $id ?: 'uomDropdown_' . substr(md5($title . mt_rand()), 0, 8);
    }

    public function render()
    {
        return view('uom::components.uom.sidebar-item-dropdown');
    }
}
