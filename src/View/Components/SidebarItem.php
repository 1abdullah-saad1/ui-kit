<?php

namespace Uom\UiKit\View\Components;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class SidebarItem extends Component
{
    public string $title;
    public ?string $icon;
    public ?string $route;
    public ?string $role;
    public ?string $can;

    public function __construct(string $title, ?string $icon = null, ?string $route = null, ?string $role = null, ?string $can = null)
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->route = $route;
        $this->role = $role;
        $this->can = $can;
    }

    public function shouldRender(): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        // Role filter: allow if user has any of the listed roles
        if ($this->role) {
            $roles = array_filter(array_map('trim', explode('|', $this->role)));
            $hasRole = false;
            foreach ($roles as $r) {
                if (method_exists($user, 'hasRole') && $user->hasRole($r)) {
                    $hasRole = true;
                    break;
                }
            }
            if (!$hasRole) return false;
        }

        // Permission filter: allow if user can any of listed permissions
        if ($this->can) {
            $perms = array_filter(array_map('trim', explode('|', $this->can)));
            $hasPerm = false;
            foreach ($perms as $p) {
                if ($user->can($p)) { $hasPerm = true; break; }
            }
            if (!$hasPerm) return false;
        }

        return true;
    }

    public function render()
    {
        return view('uom::components.uom.sidebar-item');
    }
}
