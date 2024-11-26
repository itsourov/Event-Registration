<x-web-layout>

    <div class="container mx-auto px-2 my-20">


        <x-card class=" p-4 space-y-1 ">
            <div class="max-w-md mx-auto">
                <div class="aspect-w-16 aspect-h-9">
                    {{ $registration->contest->getFirstMedia("contest-banner-images") ?->img()->attributes(["class" => "w-full object-cover rounded-xl"]) }}
                </div>
            </div>
            <h3 class="font-bold text-center text-lg underline">My Registration</h3>

            <p>
                Name: <span class="font-semibold">{{$registration->name}}</span>
            </p>
            <p>
                Email: <span class="font-semibold">{{$registration->email}}</span>
            </p>
            <p>
                Student ID: <span class="font-semibold">{{$registration->student_id}}</span>
            </p>
            <p>
                Phone: <span class="font-semibold">{{$registration->phone}}</span>
            </p>
            <p>
                Gender: <span class="font-semibold">{{$registration->gender}}</span>
            </p>
            <p>
                Section: <span class="font-semibold">{{$registration->section}}</span>
            </p>
            <p>
                Department: <span class="font-semibold">{{$registration->department}}</span>
            </p>

            <p>
                T-Shirt Size: <span class="font-semibold">{{$registration->tshirt_size}}</span>
            </p>

            <p>
                Lab Teacher Name: <span class="font-semibold">{{$registration->lab_teacher_name}}</span>
            </p>
            {{--            <p>--}}
            {{--                Charged amount: <span class="font-semibold">{{$registration->extra['charged_amount']??''}}</span>--}}
            {{--            </p>--}}
            <p>
                payment method: <span class="font-semibold">{{$registration->payment_method}}</span>
            </p>
            <p>
                payment phone number: <span class="font-semibold">{{$registration->payment_phone}}</span>
            </p>
            <p>
                Transaction id: <span class="font-semibold">{{$registration->payment_transaction_id}}</span>
            </p>
            {{--            <div class="text-success-500 text-info-500"></div>--}}
            <p>
                Registration status: <span
                    class="font-semibold text-lg text-{{$registration->status->getColor()}}-500">{{$registration->status}}</span>
            </p>


        </x-card>

        @if($registration->status==\App\Enums\RegistrationStatuses::PENDING)
            <div
                class="mt-4 bg-blue-100 border border-blue-200 text-sm text-blue-800 rounded-lg p-4 dark:bg-blue-800/10 dark:border-blue-900 dark:text-blue-500"
                role="alert" tabindex="-1" aria-labelledby="hs-soft-color-info-label">
                Pending means you submitted the form successfully. Now we will verify your payment and update the registration status. It may take some time because we are checking everything manually.
            </div>
        @endif
    </div>
</x-web-layout>
