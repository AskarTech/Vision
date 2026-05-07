@props([
    'label' => null,
    'id' => null,
    'type' => 'text',
    'name' => null,
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'help' => null,
    'error' => null,
])

<div class="space-y-1.5">
    @if($label)
    <label for="{{ $id }}" class="block text-sm font-medium text-slate-300">
        {{ $label }}
        @if($required)
        <span class="text-rose-400">*</span>
        @endif
    </label>
    @endif

    @if($type === 'textarea')
    <textarea 
        id="{{ $id }}" 
        name="{{ $name }}" 
        rows="4"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $readonly ? 'readonly' : '' }}
        class="w-full rounded-lg border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-slate-100 placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-1 focus:ring-emerald-400 disabled:opacity-50"
    >{{ $value }}</textarea>
    @elseif($type === 'select')
    <select 
        id="{{ $id }}" 
        name="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        class="w-full rounded-lg border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-slate-100 focus:border-emerald-400 focus:outline-none focus:ring-1 focus:ring-emerald-400 disabled:opacity-50"
    >
        {{ $slot }}
    </select>
    @else
    <input 
        type="{{ $type }}" 
        id="{{ $id }}" 
        name="{{ $name }}" 
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $readonly ? 'readonly' : '' }}
        class="w-full rounded-lg border border-white/10 bg-white/5 px-4 py-2.5 text-sm text-slate-100 placeholder:text-slate-500 focus:border-emerald-400 focus:outline-none focus:ring-1 focus:ring-emerald-400 disabled:opacity-50"
    />
    @endif

    @if($help && !$error)
    <p class="text-xs text-slate-500">{{ $help }}</p>
    @endif

    @if($error)
    <p class="text-xs text-rose-400">{{ $error }}</p>
    @endif
</div>
