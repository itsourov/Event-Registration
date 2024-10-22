<x-web-layout>
    <div class="flex flex-col h-screen justify-between overflow-auto">

        @include('inc.header')

        <div class="flex h-full">
            <div class="grow flex flex-col justify-between md:mx-0">
                <div class=" md:ml-[13%] md:mt-[3%] text-2xl md:text-4xl font-semibold  text-slate-800 pb-20">
                    <livewire:registration-form/>
                </div>
            </div>
            <div class="sr-only bg-lime-100"></div>

            <div class="md:w-[25%] items-end justify-end hidden md:flex flex-col"><img
                    src="https://s3.ap-south-1.amazonaws.com/www.prepbytes.com/images/summer-program/mastheads/competitive.png"
                    alt="government" class="w-[50%] md:w-[100%]"></div>
        </div>
    </div>
</x-web-layout>
