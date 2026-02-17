<?php

namespace Uom\UiKit\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class UserDropdown extends Component
{
    public function render()
    {
        return view('uom::components.uom.user-dropdown', [
            'user' => Auth::user(),
        ]);
    }
}
