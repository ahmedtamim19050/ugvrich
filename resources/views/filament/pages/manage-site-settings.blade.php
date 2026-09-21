<x-filament-panels::page>
    {{-- Inline spacing: the admin panel has no theme build of its own, so
         utility classes written here are not guaranteed to exist in its CSS. --}}
    <form wire:submit="save" style="display:flex; flex-direction:column; gap:1.5rem;">
        {{ $this->form }}

        <div style="display:flex; justify-content:flex-end; padding-top:0.5rem; padding-bottom:1rem;">
            <x-filament::button type="submit" size="lg">
                Save changes
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
