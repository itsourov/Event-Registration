<x-web-layout>

    <div class="flex flex-col h-screen justify-between overflow-auto">


        @include('inc.header')

        {{-- Notice --}}
        <section class="md:mt-[2%] flex items-center ">
            <div class="bg-yellow-600 p-3 w-52 rounded-r-full text-right hidden md:block">
                <button class="text-lg md:text-xl text-white font-semibold pr-6">Notice</button>
            </div>
            <div class="flex-grow">
                <marquee behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();"><a
                        class="cursor-pointer text-red-500 text-lg" href="/notice"> Something important happened and
                        you
                        need to
                        know that. What happened? I dont know.</a>
                </marquee>

            </div>
        </section>

        <div class="flex h-full px-2 py-10">
            <div class="grow flex flex-col justify-between md:mx-0">
                <div class="ml-4 md:ml-[13%] md:mt-[3%] text-xl md:text-4xl font-semibold  text-slate-800">
                    <blockquote>
                        <span class="leading-relaxed">Online registration for<span class="text-yellow-600">
                                {{$site_settings->contest_name}} </span></span><br class="hidden lg:block"><span>
                            Programming Contest, {{$site_settings->semester}}</span>
                    </blockquote>
                </div>
                <div class="grid md:grid-cols-5 gap-4 md:mt-3 mt-2 md:ml-[13%] mr-0 md:mr-12">

                    <a href="{{auth()->user() ? '#' : route('login')}}"
                        class="md:col-span-2 h-20 w-full bg-cyan-900 {{auth()->user() ? 'cursor-not-allowed' : ''}}  text-white flex rounded-md place-items-center">
                        <span
                            class="rounded-full  {{auth()->user() ? 'bg-green-500' : 'bg-yellow-500'}} h-8 md:h-10 w-8 md:w-10 mx-2 md:mx-3 text-lg md:text-xl flex items-center flex-shrink-0 justify-center font-semibold">{{auth()->user() ? svg('heroicon-o-check', class: 'h-5 w-5') : '1'}}</span>

                        <div>
                            <span class="text-lg md:text-xl font-medium mr-2 text-white">Connect DIU email</span>
                            @auth
                                <p>{{auth()->user()->email}}</p>
                            @endauth

                        </div>

                    </a>
                    <a href="{{route('registration.create')}}"
                        class="md:col-span-3 h-20 w-full bg-cyan-900  text-white inline-flex rounded-md place-items-center">
                        <span
                            class="rounded-full bg-yellow-500 h-8 md:h-10 w-8 md:w-10 mx-2 md:mx-3 text-lg md:text-xl flex items-center flex-shrink-0 justify-center font-semibold">2</span><span
                            class="text-lg md:text-xl font-medium mr-2 text-white">Registration: Form Fill-up
                            <x-heroicon-o-arrow-right class="w-5 h-5 inline" /> Payment <span
                                class="text-base font-normal ng-star-inserted">(till
                                {{Carbon\Carbon::parse($site_settings->registration_deadline)->format('d M')}})</span>
                            <!----></span>
                    </a>
                    <a href="#"
                        class="md:col-start-1 md:col-span-2 h-20 w-full bg-cyan-900  text-start py-7 text-white inline-flex items-center rounded-md place-items-center cursor-not-allowed"
                        tabindex="0"><span
                            class="rounded-full bg-yellow-500 h-8 md:h-10 w-8 md:w-10 mx-2 md:mx-3 text-lg md:text-xl flex items-center flex-shrink-0 justify-center font-semibold">3</span><span
                            class="text-lg md:text-xl font-medium mr-2">Check Room Number</span>
                    </a>
                    <div
                        class="md:col-span-3 lg:col-span-1 h-20 w-full bg-cyan-900  text-start py-7 text-white inline-flex items-center flex-shrink-0 rounded-md place-items-center">
                        <span
                            class="rounded-full bg-yellow-500 h-8 md:h-10 w-8 md:w-10 mx-2 md:mx-3 text-lg md:text-xl flex items-center flex-shrink-0 justify-center font-semibold">4</span>
                        <div class="flex flex-wrap items-center"><span
                                class="text-lg md:text-lg font-medium mr-2">Preliminary</span><span
                                class="text-base font-normal ng-star-inserted">({{Carbon\Carbon::parse($site_settings->preliminary_date)->format('d M')}})</span>

                        </div>
                    </div>
                    <div class="md:col-span-2 lg:col-span-1 h-20 w-full bg-cyan-900  text-start py-7 text-white inline-flex items-center rounded-md place-items-center cursor-pointer"
                        tabindex="0"><span
                            class="rounded-full bg-yellow-500 h-8 md:h-10 w-8 md:w-10 mx-2 md:mx-3 text-lg md:text-xl flex items-center flex-shrink-0 justify-center font-semibold">5</span>
                        <div class="flex  flex-wrap items-center"><span
                                class="text-lg md:text-xl font-medium mr-2">Final</span><span
                                class="text-base font-normal ng-star-inserted">({{Carbon\Carbon::parse($site_settings->final_date)->format('d M')}})</span>
                            <!---->
                        </div>
                    </div>
                    <div
                        class="md:col-span-3 lg:col-span-1 h-20 w-full bg-cyan-900  text-start py-7 text-white inline-flex items-center rounded-md place-items-center">
                        <span
                            class="rounded-full bg-yellow-500 h-8 md:h-10 w-8 md:w-10 mx-2 md:mx-3 text-lg md:text-xl flex items-center flex-shrink-0 justify-center font-semibold">6</span>
                        <div class="flex  flex-wrap  items-center"><span class="text-lg md:text-xl font-medium mr-2">Result</span><span
                                class="text-base font-normal">({{Carbon\Carbon::parse($site_settings->final_date)->format('d M')}})</span>
                        </div>
                    </div>
                </div>
                <div class="mt-3 md:mt-6">
                    <div
                        class="mt-6 md:mt-0 flex flex-col md:flex-row gap-5 items-start md:items-center justify-center md:justify-start text-center  ">
                        <a href="{{auth()->user() ? '#' : route('login')}}"
                            class="flex bg-yellow-500 rounded-md md:rounded-l-none md:rounded-r-full w-full md:w-[418px] h-16 justify-end p-0 md:pr-10 ">
                            <button id="btn_primary_eligibility"
                                class="text-lg md:text-2xl font-bold w-full text-white {{auth()->user() ? 'cursor-not-allowed' : ''}}">
                                Connect DIU email
                            </button>
                        </a>
                        <div id="countDown" class="flex flex-col md:pl-10 md:-mt-6">
                            <div class="text-start text-xl mb-2 text-skin-green">
                                {{$site_settings->countdown_text}}
                            </div>
                            <div class="flex flex-row flex-wrap  gap-2 "
                                x-data="countdown('{{$site_settings->countdown_time}}')">
                                <div
                                    class="flex flex-col items-center justify-center bg-yellow-500 rounded-lg text-white px-2.5">
                                    <span class="countdown"><span id="days" class="text-base md:text-3xl font-bold"><span
                                                x-text="daysLeft"></span></span><span
                                            class="text-lg ml-2">Days</span></span>
                                </div>
                                <div
                                    class="flex flex-col items-center justify-center bg-yellow-500 rounded-lg text-white px-2.5">
                                    <span class="countdown"><span id="hours" class="text-base md:text-3xl font-bold"><span
                                                x-text="hoursLeft"></span></span><span
                                            class="text-lg ml-2">Hours</span></span>
                                </div>
                                <div
                                    class="flex flex-col items-center justify-center bg-yellow-500 rounded-lg text-white px-2.5">
                                    <span class="countdown"><span id="minutes"
                                            class="text-base md:text-3xl font-bold"><span
                                                x-text="minutesLeft"></span></span><span
                                            class="text-lg ml-2">Minutes</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:ml-[12%]">
                    <app-footer>
                        <footer class="py-4">
                            <div class="inline-flex space-x-4 md:space-x-8"><a href="https://daffodilvarsity.edu.bd/"
                                    target="_blank"><img src="https://icpc.daffodilvarsity.edu.bd/images/diu-logo.png"
                                        class="md:h-12 h-10 transform transition duration-500 hover:scale-150"
                                        alt="DIU LOGO"></a>
                                <a href="https://daffodilvarsity.edu.bd/" target="_blank"><img
                                        src="{{asset('images/diu-cse-transparent.webp')}}"
                                        class="md:h-12 h-10 transform transition duration-500 hover:scale-150"
                                        alt="DIU CSE Logo"></a>

                                <a href="https://cpc.daffodilvarsity.edu.bd/" target="_blank"><img
                                        src="https://cpc.daffodilvarsity.edu.bd/static/media/CPC-Logo.769e206fb7f2ed4a7f95.png"
                                        class="md:h-12 h-10 p-2 transform transition duration-500 hover:scale-150"
                                        alt="DIU CPC Logo"></a>

                                <a href="http://diuacm.com" target="_blank"><img
                                        src="{{ asset('images/diuacm-transparent.webp') }}"
                                        class="md:h-12 h-10 transform transition duration-500 hover:scale-150"
                                        alt="DIU ACM Logo"></a>

                            </div>
                            <div>
                                <p class="text-xs md:text-base font-light pt-2 text-skin-black">©All rights reserved to
                                    DIU
                                    ACM ,2024</p>
                                <p class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="h-4 w-4">
                                        <path
                                            d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z">
                                        </path>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                    </svg>
                                    <a href="mailto:support@acas.edu.bd" class="ml-2 text-red-500">uta@diuacm.com</a>
                                </p>
                            </div>
                        </footer>
                    </app-footer>
                </div>
            </div>

            <div class="md:w-[25%] items-end justify-end hidden md:flex flex-col"><img
                    src="https://s3.ap-south-1.amazonaws.com/www.prepbytes.com/images/summer-program/mastheads/competitive.png"
                    alt="government" class="w-[50%] md:w-[100%]"></div>
        </div>
    </div>


    <script>
        function countdown(targetDate) {
            return {
                targetDate: new Date(targetDate), // Convert the target date string to a Date object
                daysLeft: null,
                hoursLeft: null,
                minutesLeft: null,

                init() {
                    this.calculateTimeLeft();
                    setInterval(() => this.calculateTimeLeft(), 1000); // Update every second
                },

                calculateTimeLeft() {
                    const now = new Date();
                    const timeDiff = this.targetDate - now; // Get the time difference in milliseconds

                    if (timeDiff > 0) {
                        this.daysLeft = Math.floor(timeDiff / (1000 * 60 * 60 * 24)); // Days left
                        this.hoursLeft = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)); // Hours left
                        this.minutesLeft = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60)); // Minutes left
                    } else {
                        this.daysLeft = 0;
                        this.hoursLeft = 0;
                        this.minutesLeft = 0;
                    }
                }
            }
        }
    </script>
</x-web-layout>
