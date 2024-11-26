<x-web-layout>
    <div class=" container mx-auto px-2 py-14 space-y-8">
        <h3 class="text-center text-xl font-bold">{{$contest->name}}</h3>
        <livewire:registration-form :contest="$contest"/>
    </div>
</x-web-layout>
