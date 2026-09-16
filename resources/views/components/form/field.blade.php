@props([
    'name',
    'label',
    'type' => 'text',
    'required' => false,
    'placeholder' => null,
    'icon' => null,
])

<div>
    <label for="{{ $name }}" class="mb-2 block text-[13px] font-semibold text-ink-700">
        {{ $label }}
        @if ($required)
            <span class="text-brand-600">*</span>
        @endif
    </label>

    <div class="group relative">
        @if ($icon)
            <x-ui-icon :name="$icon" class="pointer-events-none absolute left-4 top-1/2 h-[18px] w-[18px] -translate-y-1/2 text-ink-400 transition-colors group-focus-within:text-brand-600" />
        @endif

        <input id="{{ $name }}" type="{{ $type }}" name="{{ $name }}" value="{{ old($name) }}"
               @if ($required) required @endif
               placeholder="{{ $placeholder }}"
               @error($name) aria-invalid="true" @enderror
               {{ $attributes->class([
                   'w-full rounded-2xl border bg-ink-50/60 py-3.5 pr-4 text-[15px] text-ink-900 placeholder:text-ink-400 transition focus:bg-white focus:outline-none focus:ring-4',
                   'pl-11' => $icon,
                   'pl-4' => ! $icon,
                   'border-red-300 focus:border-red-400 focus:ring-red-100' => $errors->has($name),
                   'border-ink-200 hover:border-ink-300 focus:border-brand-400 focus:ring-brand-100' => ! $errors->has($name),
               ]) }}>
    </div>

    @error($name)
        <p class="mt-1.5 text-[13px] text-red-600">{{ $message }}</p>
    @enderror
</div>
