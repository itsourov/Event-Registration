<div>
    <div class="space-y-3 mt-4 mx-auto">


        <x-filament::input.wrapper class="max-w-sm mx-auto"
                                   suffix-icon="heroicon-o-magnifying-glass">
            <x-filament::input
                placeholder="Search Here"
                type="text"

                wire:model.live.debounce="search"
            />
        </x-filament::input.wrapper>
        <div class=" mx-auto w-fit">
            <x-filament::tabs>
                @foreach(\App\Enums\RegistrationStatuses::cases() as $tab)

                    <x-filament::tabs.item :icon="$tab->getIcon()"
                                           :active="$activeTab === $tab->value"
                                           wire:click="$set('activeTab', '{{$tab->value}}')">
                        {{$tab->getLabel()}}

                    </x-filament::tabs.item>
                @endforeach


            </x-filament::tabs>
        </div>
        @if($search)
            <p class="text-sm">You are searching for: {{$search}} </p>
        @endif


        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 ">

            @foreach ($registrations as $registration)

                <div class="p-4 bg-cyan-900 rounded">


                    <h2 class="font-semibold text-xl text-white">
                        {{ $registration->name }}
                    </h2>
                    <p class="text-sm text-gray-300">
                        ID: {{$registration->student_id}}
                    </p>
                    <p class="mb-1 text-sm text-gray-300">
                        {{ $registration->extra['date']??"" }}
                    </p>


                </div>

            @endforeach

        </div>
        <div class="my-5">
            <x-filament::pagination :paginator="$registrations"/>

        </div>
    </div>

</div>
