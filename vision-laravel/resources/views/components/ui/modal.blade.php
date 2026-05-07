@props([
    'model' => null,
    'action' => '#',
    'method' => 'POST',
    'title' => 'Modal',
    'submitLabel' => 'حفظ',
    'cancelUrl' => '#',
    'size' => 'md', // sm, md, lg, xl
])

@php
$sizeClasses = [
    'sm' => 'max-w-md',
    'md' => 'max-w-lg',
    'lg' => 'max-w-2xl',
    'xl' => 'max-w-4xl',
];
$maxWidth = $sizeClasses[$size] ?? 'max-w-lg';
@endphp

<div x-data="{ open: false }" {{ $attributes }}>
    <!-- Trigger -->
    @if($slot->isEmpty())
        <button @click="open = true" type="button" class="btn btn-primary">
            {{ $trigger ?? 'Open Modal' }}
        </button>
    @endif

    <!-- Modal Backdrop -->
    <div x-show="open" 
         x-cloak
         @keydown.escape.window="open = false"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        
        <!-- Backdrop -->
        <div x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/60 backdrop-blur-sm"
             @click="open = false"></div>

        <!-- Modal Panel -->
        <div class="flex min-h-full items-center justify-center p-4">
            <div x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative w-full {{ $maxWidth }} rounded-2xl border border-white/10 bg-slate-900 shadow-2xl">
                
                <!-- Header -->
                <div class="flex items-center justify-between border-b border-white/10 px-6 py-4">
                    <h3 class="text-lg font-bold text-white">{{ $title }}</h3>
                    <button @click="open = false" class="text-slate-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <form method="{{ $method === 'GET' ? 'GET' : 'POST' }}" action="{{ $action }}">
                    @if($method !== 'GET')
                        @csrf
                        @if($method !== 'POST')
                            @method($method)
                        @endif
                    @endif
                    
                    <div class="px-6 py-4">
                        {{ $slot }}
                    </div>

                    <!-- Footer -->
                    <div class="flex items-center justify-end gap-3 border-t border-white/10 px-6 py-4">
                        <a href="{{ $cancelUrl }}" @click.prevent="open = false" 
                           class="btn btn-ghost btn-sm text-slate-300">
                            إلغاء
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm">
                            {{ $submitLabel }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
