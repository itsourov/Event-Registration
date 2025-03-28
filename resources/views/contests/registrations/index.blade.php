<x-web-layout>
    <div class="container mx-auto py-8 px-4">
        <!-- Contest Details Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700 transition-colors mb-8">
            <div class="relative">
                @if($contest->getFirstMediaUrl('contest-banner-images'))
                    <div class="aspect-w-16 aspect-h-9 max-h-72">
                        <img
                            src="{{ $contest->getFirstMediaUrl('contest-banner-images', 'medium') }}"
                            alt="{{ $contest->name }}"
                            class="w-full h-full object-cover"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-6">
                            <span class="inline-block px-3 py-1 mb-3 text-xs font-medium tracking-wider uppercase bg-indigo-600 bg-opacity-85 text-white rounded-full">Contest</span>
                            <h1 class="text-white font-bold text-2xl md:text-3xl drop-shadow-sm">{{ $contest->name }}</h1>
                        </div>
                    </div>
                @else
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $contest->name }}</h1>
                    </div>
                @endif
            </div>

            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-4">
                        <div class="flex items-center text-gray-700 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <span class="font-semibold text-gray-900 dark:text-white">Registration Deadline:</span>
                                <span class="ml-2">{{ $contest->registration_deadline ? $contest->registration_deadline->format('M d, Y') : 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center text-gray-700 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <span class="font-semibold text-gray-900 dark:text-white">Registration Fee:</span>
                                <span class="ml-2">{{ $contest->registration_fee ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center text-gray-700 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <div>
                                <span class="font-semibold text-gray-900 dark:text-white">Total Registrations:</span>
                                <span class="ml-2">{{ $contest->registrations->count() }}</span>
                            </div>
                        </div>
                      
                    </div>
                </div>

                <div class="prose max-w-none dark:prose-invert prose-img:rounded-lg prose-headings:font-bold prose-a:text-blue-600 dark:prose-a:text-blue-400">
                    {!! $contest->description !!}
                </div>
            </div>
        </div>

        <!-- Registration Statistics Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700 transition-colors">
            <div class="p-6 md:p-8">
                <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Registration Statistics by Section
                </h2>

                @if(count($sectionCounts) > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($sectionCounts as $section => $count)
                            <a href="{{ route('contests.registrations.section', ['contest' => $contest->slug, 'section' => $section]) }}"
                               class="bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 
                                      rounded-xl p-5 border border-indigo-100 dark:border-indigo-800/30
                                      hover:shadow-md transition-all transform hover:-translate-y-1 
                                      focus:outline-none focus:ring-2 focus:ring-indigo-500 group">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="font-semibold text-xl text-gray-800 dark:text-white">{{ $section }}</h3>
                                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300">
                                        Section
                                    </span>
                                </div>
                                <p class="text-indigo-600 dark:text-indigo-400 font-bold text-2xl flex items-center">
                                    <span class="mr-2">{{ $count }}</span>
                                    <span class="text-sm font-normal text-gray-600 dark:text-gray-400">{{ Str::plural('Registration', $count) }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-auto text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </p>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 text-yellow-700 dark:text-yellow-400 p-5 rounded-xl flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p>No registrations found for this contest.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-web-layout>
