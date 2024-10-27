<x-web-layout>

    @section("seo")
        {!! seo($SEOData) !!}
    @endsection


        <div class="flex h-full px-2 py-10 ">
            <div class="grow flex flex-col justify-between md:mx-0 space-y-14">
                <div
                    class="ml-4 md:ml-[13%] md:mt-[3%] text-xl md:text-4xl font-semibold  text-slate-800 dark:text-slate-100">
                    <blockquote>
                        <span class="leading-relaxed">Online registration for<span class="text-yellow-600">
                                {{$contest->name}} </span></span>
                    </blockquote>
                </div>

                <div class="flex flex-wrap gap-2 md:ml-[13%] md:mt-[3%]">
                    <a href="{{auth()->user() ? '#' : route('login')}}"
                       class="bg-cyan-900 flex-grow rounded-md px-3 py-4 flex items-center {{auth()->user() ? 'cursor-not-allowed' : ''}}">
                         <span
                             class="rounded-full  {{auth()->user() ? 'bg-green-500' : 'bg-yellow-500'}} h-8 md:h-10 w-8 md:w-10 mx-2 md:mx-3 text-lg md:text-xl flex items-center flex-shrink-0 justify-center font-semibold">{{auth()->user() ? svg('heroicon-o-check', class: 'h-5 w-5') : '1'}}</span>

                        <div>
                            <span class="text-lg md:text-xl font-medium mr-2 text-white">Connect DIU email</span>
                            <p class="text-white">{{auth()->user()?->email}}</p>
                        </div>
                    </a>
                    <a href="{{route('contests.registration.form',$contest)}}"
                       class="bg-cyan-900 flex-grow rounded-md px-3 py-4 flex items-center">
                        @if($registered)
                            <span
                                class="rounded-full  {{auth()->user() ? 'bg-green-500' : 'bg-yellow-500'}} h-8 md:h-10 w-8 md:w-10 mx-2 md:mx-3 text-lg md:text-xl flex items-center flex-shrink-0 justify-center font-semibold">{{auth()->user() ? svg('heroicon-o-check', class: 'h-5 w-5') : '1'}}</span>

                        @else
                            <span
                                class="rounded-full bg-yellow-500 h-8 md:h-10 w-8 md:w-10 mx-2 md:mx-3 text-lg md:text-xl flex items-center flex-shrink-0 justify-center font-semibold">
                            2
                        </span>
                        @endif

                        <div>
                            <span class="text-lg md:text-xl font-medium mr-2 text-white">
                                Register Now
                            </span>
                            <p class="text-white">Last Date: {{$contest->registration_deadline->format('d M Y')}}</p>
                        </div>
                    </a>

                    <a href="{{route('contests.registration.form',$contest)}}"
                       class="bg-cyan-900 flex-grow rounded-md px-3 py-4 flex items-center">
                        <span
                            class="rounded-full bg-yellow-500 h-8 md:h-10 w-8 md:w-10 mx-2 md:mx-3 text-lg md:text-xl flex items-center flex-shrink-0 justify-center font-semibold">
                            3
                        </span>
                        <div>
                            <span class="text-lg md:text-xl font-medium mr-2 text-white">
                                Check registration status
                            </span>

                        </div>
                    </a>


                    @php
                        $cnt =4;
                    @endphp
                    @foreach($contest->dates as $date)
                        <a href="{{($date['link']??false) ? $date['link'] :'#'}}"
                           class="bg-cyan-900 flex-grow rounded-md px-3 py-4 flex items-center">
                            <span
                                class="rounded-full bg-yellow-500 h-8 md:h-10 w-8 md:w-10 mx-2 md:mx-3 text-lg md:text-xl flex items-center flex-shrink-0 justify-center font-semibold">
                               {{$cnt}}
                            </span>
                            <div>
                                <span
                                    class="text-lg md:text-xl font-medium mr-2 text-white">{{$date['round_name']??''}}</span>
                                <p class="text-white">
                                    Date: {{Carbon\Carbon::parse($date['round_date'])?->format('d M Y')}}</p>
                            </div>
                        </a>
                    @endforeach
                </div>


                <div class="mt-3 md:mt-6 flex justify-center">
                    <div id="countDown" class="flex flex-col md:pl-10 md:-mt-6">
                        <div class=" text-xl mb-2 text-skin-green text-center">
                            {{$contest->countdown_text}}
                        </div>


                        <div class="flex flex-row flex-wrap justify-center  gap-2 "
                             x-data="countdown('{{$contest->countdown_time}}')">
                            <div
                                class="flex flex-col items-center justify-center bg-yellow-500 rounded-lg text-white p-2.5">
                                    <span class="countdown"><span id="days"
                                                                  class="text-base md:text-3xl font-bold"><span
                                                x-text="daysLeft"></span></span><span
                                            class="text-lg ml-2">Days</span></span>
                            </div>
                            <div
                                class="flex flex-col items-center justify-center bg-yellow-500 rounded-lg text-white p-2.5">
                                    <span class="countdown"><span id="hours"
                                                                  class="text-base md:text-3xl font-bold"><span
                                                x-text="hoursLeft"></span></span><span
                                            class="text-lg ml-2">Hours</span></span>
                            </div>
                            <div
                                class="flex flex-col items-center justify-center bg-yellow-500 rounded-lg text-white p-2.5">
                                    <span class="countdown"><span id="minutes"
                                                                  class="text-base md:text-3xl font-bold"><span
                                                x-text="minutesLeft"></span></span><span
                                            class="text-lg ml-2">Minutes</span></span>
                            </div>

                            <div
                                class="flex flex-col items-center justify-center bg-yellow-500 rounded-lg text-white p-2.5">
                                    <span class="countdown"><span id="seconds"
                                                                  class="text-base md:text-3xl font-bold"><span
                                                x-text="secondsLeft"></span></span><span
                                            class="text-lg ml-2">Seconds</span></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:ml-[12%]">
                    <app-footer>
                        <footer class="py-4">
                            <div class="inline-flex space-x-4 md:space-x-8"><a href="https://daffodilvarsity.edu.bd/"
                                                                               target="_blank"><img
                                        src="{{asset('images/diu-logo.png')}}"
                                        class="md:h-12 h-10 transform transition duration-500 hover:scale-150"
                                        alt="DIU LOGO"></a>
                                <a href="https://daffodilvarsity.edu.bd/" target="_blank"><img
                                        src="{{asset('images/diu-cse-transparent.webp')}}"
                                        class="md:h-12 h-10 transform transition duration-500 hover:scale-150"
                                        alt="DIU CSE Logo"></a>

                                <a href="https://cpc.daffodilvarsity.edu.bd/" target="_blank"><img
                                        src="{{asset('images/CPC-Logo.png')}}"
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
                                    <a href="mailto:acm.diu@gmail.com" class="ml-2 text-red-500">acm.diu@gmail.com</a>
                                </p>
                            </div>
                        </footer>
                    </app-footer>
                </div>
            </div>

            <div class="md:w-[25%] items-end justify-end hidden md:flex flex-col"><img
                    src="{{asset('images/competitive.png')}}"
                    alt="government" class="w-[50%] md:w-[100%]"></div>
        </div>



    <script>
        function countdown(targetDate) {
            return {
                // Create the target date assuming it's in BST (Asia/Dhaka)
                targetDate: new Date(targetDate + ' +06:00'), // Append '+06:00' to ensure it's treated as BST
                daysLeft: null,
                hoursLeft: null,
                minutesLeft: null,
                secondsLeft: null,

                init() {
                    this.calculateTimeLeft();
                    setInterval(() => this.calculateTimeLeft(), 1000); // Update every second
                },

                calculateTimeLeft() {
                    const now = new Date(); // Get the current local time
                    const timeDiff = this.targetDate - now; // Get the time difference in milliseconds

                    if (timeDiff > 0) {
                        this.daysLeft = Math.floor(timeDiff / (1000 * 60 * 60 * 24)); // Days left
                        this.hoursLeft = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)); // Hours left
                        this.minutesLeft = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60)); // Minutes left
                        this.secondsLeft = Math.floor((timeDiff % (1000 * 60)) / 1000); // Seconds left
                    } else {
                        this.daysLeft = 0;
                        this.hoursLeft = 0;
                        this.minutesLeft = 0;
                        this.secondsLeft = 0;
                    }
                }
            }
        }

    </script>
</x-web-layout>
