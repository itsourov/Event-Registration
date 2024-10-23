<x-web-layout>
    <section class="bg-slate-100 py-10 dark:bg-gray-900 md:py-20">
        <div class="container mx-auto px-2">
            <h2
                class="text-center font-marry text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                Programming Contests
            </h2>
            <div
                class="mt-4 grid grid-cols-1 gap-5 md:grid-cols-2">
                @foreach ($contests as $contest)
                    <a class="" href="{{ route("contests.show", $contest->slug) }}">
                        <x-card class="group flex flex-col h-full justify-between">
                            <div>
                                <div class="aspect-w-16 aspect-h-9">
                                    {{ $contest->getFirstMedia("contest-banner-images") ?->img()->attributes(["class" => "w-full object-cover rounded-xl"]) }}
                                </div>
                                <div class="my-6">
                                    <h3
                                        class="line-clamp-2 text-xl font-semibold text-gray-800 dark:text-neutral-300 dark:group-hover:text-white">
                                        {{ $contest->name }} Programming Contest, {{$contest->semester}}
                                    </h3>
                                    <p
                                        class="mt-5 text-gray-600 dark:text-neutral-400">
                                        {!! Str::limit(strip_tags($contest->description)) !!}
                                    </p>
                                </div>
                            </div>

                            <x-button.primary>
                                Register Now
                            </x-button.primary>
                        </x-card>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

</x-web-layout>
