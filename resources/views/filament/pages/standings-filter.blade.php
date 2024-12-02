<x-filament-panels::page>
    <x-filament-panels::form wire:submit="process">
        {{ $this->form }}
        <x-filament-panels::form.actions
            :actions="$this->getFormActions()"
        />
    </x-filament-panels::form>

    <div class="mt-2">

        <div>
            {{ $this->table }}
        </div>
    </div>


</x-filament-panels::page>
