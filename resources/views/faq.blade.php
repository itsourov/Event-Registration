<x-web-layout>
    @include('inc.header')


    <!-- FAQ -->
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
        <!-- Title -->
        <div class="max-w-2xl mx-auto mb-10 lg:mb-14">
            <h2 class="text-2xl font-bold md:text-4xl md:leading-tight">You might be wondering...</h2>
        </div>
        <!-- End Title -->

        @php
            $faq = [
    [
        'q' => 'Who can register for the event?',
        'a' => 'Only students with a valid university email address (e.g., @diu.edu.bd) can register for the programming contest. Please use your official university email to sign up.'
    ],[
         'q' => 'How will I know if my registration was successful?',
        'a' => "After registering and successfully completing your payment, you will receive a confirmation email with your registration details. You can also click this <a class='text-blue-600' href='".route('all-registrations')."'>Link</a> to view your registration status."
    ],
    [
        'q' => 'What if I don’t receive a confirmation email after registering?',
        'a' => 'If you don’t receive a confirmation email within 10 minutes of registering, please check your spam or junk folder. If you still can’t find it, contact our support team.'
    ],
    [
        'q' => 'How can I pay for the event registration?',
        'a' => 'You can pay through our supported mobile banking services. Detailed payment instructions will be provided during the registration process.'
    ],
    [
        'q' => 'What if my payment is not showing up on the website?',
        'a' => 'Sometimes it might take a little time for our system to update your payment information. If you see a "Transaction not found" message, don’t worry—your payment won’t be lost. We will update the status as soon as it is processed. Please allow up to 6 hour for the update.'
    ],
    [
        'q' => 'Can I cancel or change my registration?',
        'a' => 'Unfortunately, once you have registered and paid, the registration is final. We do not allow cancellations or changes to the registration details.'
    ],
    [
        'q' => 'What should I do if I entered the wrong information during registration?',
        'a' => 'If you entered incorrect information, please contact our support team immediately at [support email]. Be sure to include your registration details and the correction needed.'
    ],
    [
        'q' => 'Is there a deadline for registration?',
        'a' => 'Yes, the deadline for registration is '.Carbon\Carbon::createFromFormat('d-m-Y H:i:s', env('REGISTRATION_DEADLINE'))->format('d M Y').'. Make sure you register and complete your payment by this date to secure your spot in the contest.'
    ],



];

        @endphp

        <div class="max-w-2xl mx-auto divide-y divide-gray-200">

            @foreach($faq as $qa)
                <div class="py-8 first:pt-0 last:pb-0">
                    <div class="flex gap-x-5">
                        <svg class="shrink-0 mt-1 size-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24"
                             height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                            <path d="M12 17h.01"/>
                        </svg>

                        <div class="grow">
                            <h3 class="md:text-lg font-semibold text-gray-800">
                                {{$qa['q']}}
                            </h3>
                            <p class="mt-1 text-gray-500">
                                {!! $qa['a'] !!}
                            </p>
                        </div>
                    </div>
                </div>

            @endforeach



        </div>
    </div>
    <!-- End FAQ -->
</x-web-layout>
