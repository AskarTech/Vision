/**
 * YemenWi-Fi Hub - Modern Theme Configuration
 * 
 * A professional, modern color palette optimized for:
 * - RTL Arabic support
 * - High contrast accessibility
 * - Professional business appearance
 * - Eye-friendly for long usage sessions
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Primary Brand Colors
    |--------------------------------------------------------------------------
    |
    | Main brand identity colors used throughout the application
    |
    */
    'primary' => [
        '50' => '#eff6ff',
        '100' => '#dbeafe',
        '200' => '#bfdbfe',
        '300' => '#93c5fd',
        '400' => '#60a5fa',
        '500' => '#3b82f6', // Main primary
        '600' => '#2563eb',
        '700' => '#1d4ed8',
        '800' => '#1e40af',
        '900' => '#1e3a8a',
        '950' => '#172554',
    ],

    /*
    |--------------------------------------------------------------------------
    | Secondary/Accent Colors
    |--------------------------------------------------------------------------
    |
    | Complementary colors for accents, highlights, and CTAs
    |
    */
    'secondary' => [
        '50' => '#f5f3ff',
        '100' => '#ede9fe',
        '200' => '#ddd6fe',
        '300' => '#c4b5fd',
        '400' => '#a78bfa',
        '500' => '#8b5cf6', // Main secondary
        '600' => '#7c3aed',
        '700' => '#6d28d9',
        '800' => '#5b21b6',
        '900' => '#4c1d95',
        '950' => '#2e1065',
    ],

    /*
    |--------------------------------------------------------------------------
    | Success Colors (Green/Teal)
    |--------------------------------------------------------------------------
    |
    | Used for positive actions, approvals, completed states
    |
    */
    'success' => [
        '50' => '#f0fdf4',
        '100' => '#dcfce7',
        '200' => '#bbf7d0',
        '300' => '#86efac',
        '400' => '#4ade80',
        '500' => '#22c55e', // Main success
        '600' => '#16a34a',
        '700' => '#15803d',
        '800' => '#166534',
        '900' => '#14532d',
        '950' => '#052e16',
    ],

    /*
    |--------------------------------------------------------------------------
    | Warning Colors (Amber/Orange)
    |--------------------------------------------------------------------------
    |
    | Used for warnings, pending states, cautions
    |
    */
    'warning' => [
        '50' => '#fffbeb',
        '100' => '#fef3c7',
        '200' => '#fde68a',
        '300' => '#fcd34d',
        '400' => '#fbbf24',
        '500' => '#f59e0b', // Main warning
        '600' => '#d97706',
        '700' => '#b45309',
        '800' => '#92400e',
        '900' => '#78350f',
        '950' => '#451a03',
    ],

    /*
    |--------------------------------------------------------------------------
    | Danger/Error Colors (Red)
    |--------------------------------------------------------------------------
    |
    | Used for errors, rejections, destructive actions
    |
    */
    'danger' => [
        '50' => '#fef2f2',
        '100' => '#fee2e2',
        '200' => '#fecaca',
        '300' => '#fca5a5',
        '400' => '#f87171',
        '500' => '#ef4444', // Main danger
        '600' => '#dc2626',
        '700' => '#b91c1c',
        '800' => '#991b1b',
        '900' => '#7f1d1d',
        '950' => '#450a0a',
    ],

    /*
    |--------------------------------------------------------------------------
    | Info Colors (Cyan/Blue)
    |--------------------------------------------------------------------------
    |
    | Used for informational messages, neutral states
    |
    */
    'info' => [
        '50' => '#ecfeff',
        '100' => '#cffafe',
        '200' => '#a5f3fc',
        '300' => '#67e8f9',
        '400' => '#22d3ee',
        '500' => '#06b6d4', // Main info
        '600' => '#0891b2',
        '700' => '#0e7490',
        '800' => '#155e75',
        '900' => '#164e63',
        '950' => '#083344',
    ],

    /*
    |--------------------------------------------------------------------------
    | Neutral Grays
    |--------------------------------------------------------------------------
    |
    | Used for text, backgrounds, borders
    | Optimized for readability and contrast
    |
    */
    'gray' => [
        '50' => '#f9fafb',
        '100' => '#f3f4f6',
        '200' => '#e5e7eb',
        '300' => '#d1d5db',
        '400' => '#9ca3af',
        '500' => '#6b7280',
        '600' => '#4b5563',
        '700' => '#374151',
        '800' => '#1f2937',
        '900' => '#111827',
        '950' => '#030712',
    ],

    /*
    |--------------------------------------------------------------------------
    | Slate (Cool Grays)
    |--------------------------------------------------------------------------
    |
    | Alternative neutral palette with blue undertones
    | Used for modern, professional look
    |
    */
    'slate' => [
        '50' => '#f8fafc',
        '100' => '#f1f5f9',
        '200' => '#e2e8f0',
        '300' => '#cbd5e1',
        '400' => '#94a3b8',
        '500' => '#64748b',
        '600' => '#475569',
        '700' => '#334155',
        '800' => '#1e293b',
        '900' => '#0f172a',
        '950' => '#020617',
    ],

    /*
    |--------------------------------------------------------------------------
    | Dashboard Specific Colors
    |--------------------------------------------------------------------------
    |
    | Specialized colors for dashboard elements
    |
    */
    'dashboard' => [
        'bg-primary' => '#ffffff',
        'bg-secondary' => '#f8fafc',
        'bg-sidebar' => '#0f172a', // Dark slate for sidebar
        'bg-card' => '#ffffff',
        'border-light' => '#e2e8f0',
        'text-primary' => '#0f172a',
        'text-secondary' => '#475569',
        'text-muted' => '#94a3b8',
        'shadow-color' => '0, 0, 0',
    ],

    /*
    |--------------------------------------------------------------------------
    | Gradient Presets
    |--------------------------------------------------------------------------
    |
    | Ready-to-use gradient combinations
    |
    */
    'gradients' => [
        'primary' => 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
        'secondary' => 'linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%)',
        'success' => 'linear-gradient(135deg, #22c55e 0%, #16a34a 100%)',
        'warning' => 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
        'danger' => 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
        'ocean' => 'linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%)',
        'sunset' => 'linear-gradient(135deg, #f59e0b 0%, #ef4444 100%)',
        'purple' => 'linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%)',
    ],

    /*
    |--------------------------------------------------------------------------
    | Shadow Configurations
    |--------------------------------------------------------------------------
    |
    | Predefined shadow styles for consistency
    |
    */
    'shadows' => [
        'sm' => '0 1px 2px 0 rgb(0 0 0 / 0.05)',
        'default' => '0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1)',
        'md' => '0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)',
        'lg' => '0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1)',
        'xl' => '0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1)',
        '2xl' => '0 25px 50px -12px rgb(0 0 0 / 0.25)',
        'inner' => 'inset 0 2px 4px 0 rgb(0 0 0 / 0.05)',
        'colored' => '0 4px 6px -1px rgb(59 130 246 / 0.2), 0 2px 4px -2px rgb(59 130 246 / 0.1)',
    ],

    /*
    |--------------------------------------------------------------------------
    | Border Radius
    |--------------------------------------------------------------------------
    |
    | Consistent border radius values
    |
    */
    'radius' => [
        'none' => '0',
        'sm' => '0.125rem',
        'default' => '0.25rem',
        'md' => '0.375rem',
        'lg' => '0.5rem',
        'xl' => '0.75rem',
        '2xl' => '1rem',
        '3xl' => '1.5rem',
        'full' => '9999px',
    ],

    /*
    |--------------------------------------------------------------------------
    | Animation Durations
    |--------------------------------------------------------------------------
    |
    | Standard animation timing
    |
    */
    'animation' => [
        'fast' => '150ms',
        'normal' => '300ms',
        'slow' => '500ms',
        'slide' => '0.3s cubic-bezier(0.4, 0, 0.2, 1)',
        'fade' => '0.2s ease-in-out',
        'bounce' => '0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55)',
    ],

    /*
    |--------------------------------------------------------------------------
    | Dark Mode Support
    |--------------------------------------------------------------------------
    |
    | Dark mode color variations
    |
    */
    'dark' => [
        'bg-primary' => '#0f172a',
        'bg-secondary' => '#1e293b',
        'bg-sidebar' => '#020617',
        'bg-card' => '#1e293b',
        'border-light' => '#334155',
        'text-primary' => '#f1f5f9',
        'text-secondary' => '#cbd5e1',
        'text-muted' => '#64748b',
    ],
];
