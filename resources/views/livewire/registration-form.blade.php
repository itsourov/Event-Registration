<div>
    <form wire:submit="create">
        {{ $this->form }}

        <button type="submit" class="bg-yellow-500 rounded-lg text-base p-3 text-white mt-2">
            Submit & Pay
        </button>
    </form>

    <x-filament-actions::modals />
</div>
