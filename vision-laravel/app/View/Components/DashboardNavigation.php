<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DashboardNavigation extends Component
{
    public string $dashboardType;

    /**
     * Create a new component instance.
     */
    public function __construct(string $dashboardType = 'admin')
    {
        $this->dashboardType = $dashboardType;
    }

    /**
     * Get the navigation items for the current dashboard type.
     */
    public function getNavigationItems(): array
    {
        $config = config('navigation.' . $this->dashboardType, []);
        
        // Filter items based on user permissions
        return $this->filterByPermissions($config);
    }

    /**
     * Filter navigation items based on user permissions.
     */
    protected function filterByPermissions(array $items): array
    {
        $user = auth()->user();
        
        if (!$user) {
            return [];
        }

        $filtered = [];

        foreach ($items as $item) {
            // If item has sub-items, filter them recursively
            if (isset($item['sub']) && is_array($item['sub'])) {
                $item['sub'] = $this->filterByPermissions($item['sub']);
                
                // Only include parent if it has visible sub-items
                if (empty($item['sub'])) {
                    continue;
                }
            }

            // Check permission if required
            if (isset($item['permission'])) {
                if (!$user->can($item['permission'])) {
                    continue;
                }
            }

            // Check role if specified
            if (isset($item['roles']) && !in_array($user->role, $item['roles'])) {
                continue;
            }

            $filtered[] = $item;
        }

        return $filtered;
    }

    /**
     * Get the active route name.
     */
    public function getActiveRoute(): string
    {
        return request()->route()->getName() ?? '';
    }

    /**
     * Check if a route is active.
     */
    public function isActive(string $routeName): bool
    {
        $currentRoute = $this->getActiveRoute();
        
        // Exact match
        if ($currentRoute === $routeName) {
            return true;
        }

        // Prefix match for grouped routes
        if (str_starts_with($currentRoute, $routeName . '.')) {
            return true;
        }

        return false;
    }

    /**
     * Render the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layout.navigation');
    }
}
