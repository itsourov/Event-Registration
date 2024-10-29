<div>

    <form wire:submit="create">
        {{ $this->form }}

{{--        <div class="flex gap-1">--}}
{{--            <button wire:loading.attr="disabled" type="submit"--}}
{{--                    class="bg-yellow-500 disabled:bg-gray-400 rounded-lg text-base p-2.5 text-white mt-2 flex items-center gap-1">--}}

{{--                Save Info--}}
{{--                <div wire:target="create" wire:loading--}}
{{--                     class="animate-spin inline-block size-5 border-[3px] border-current border-t-transparent text-blue-600 rounded-full"--}}
{{--                     role="status" aria-label="loading">--}}
{{--                    <span class="sr-only">Loading...</span>--}}
{{--                </div>--}}
{{--            </button>--}}
{{--            @if($hasRegistration)--}}
{{--                <button wire:target="payNow" wire:click.prevent="payNow" wire:loading.attr="disabled"--}}
{{--                        class="bg-green-500 disabled:bg-gray-400 rounded-lg text-base p-2.5 text-white mt-2 flex items-center gap-1">--}}

{{--                    Pay Now--}}
{{--                    <div wire:target="payNow" wire:loading--}}
{{--                         class="animate-spin inline-block size-5 border-[3px] border-current border-t-transparent text-blue-600 rounded-full"--}}
{{--                         role="status" aria-label="loading">--}}
{{--                        <span class="sr-only">Loading...</span>--}}
{{--                    </div>--}}
{{--                </button>--}}
{{--            @endif--}}

{{--        </div>--}}

    </form>

    <x-filament-actions::modals/>
</div>
