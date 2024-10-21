<header class="mt-[2%] z-10 ">
    <div class="flow-root">
        <div class="float-left md:w-[60%] mb-6 lg:mb-0">
            <div class="flex flex-row items-center"><img src="{{ asset('images/uta-logo.webp') }}"
                                                         class="w-28 h-14 mb-2 md:ml-[5%] ml-4 cursor-pointer"
                                                         alt="Logo">
                <div class="flex flex-col cursor-pointer">
                    <div class="text-lg md:text-3xl leading-normal text-cyan-900 font-semibold ml-5"> UTA
                        Registration
                    </div>
                    <div class="text-sm md:text-lg text-cyan-900 font-normal ml-5 leading-normal">
                        <blockquote>
                            <p>Organized by: ACM Wing CPC, Department Of CSE, DIU </p>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="w-full lg:w-[40%] mb-6 lg:mb-0 h-16 bg-cyan-900 rounded lg:rounded-l-full lg:pl-10 px-4 flex items-center justify-start">
            <ul
                class="w-full inline-flex cursor-pointer items-center justify-around md:justify-between gap-x-3 md:gap-x-6 space-x-reverse  text-white font-normal flex-wrap text-base">
                <li><a href="{{route('home')}}">Home</a></li>
                <li><a href="{{route('all-registrations')}}">Registrations</a></li>
                <li><a href="{{route('contact')}}">Contact</a></li>
                <li><a href="{{route('faq')}}">FAQ</a></li>
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf


                        <li><a href="{{route('logout')}}"
                               onclick="event.preventDefault();this.closest('form').submit();">Log Out</a></li>
                    </form>

                @else
                    <li><a href="{{route('login')}}">Signin</a></li>

                @endauth


            </ul>
        </div>
    </div>
</header>
