<x-web-layout>
    @include('inc.header')


    <div class="max-w-md mx-auto px-2 my-20">
        @php
            $registration= auth()->user()->registration
        @endphp

        <div class="bg-lime-100 rounded p-4 space-y-1 ">
            <h3 class="font-bold text-center text-lg underline">My Registration</h3>
            <p>
                Name: <span class="font-semibold">{{$registration->name}}</span>
            </p>
            <p>
                Email: <span class="font-semibold">{{$registration->email}}</span>
            </p>
            <p>
                Student ID: <span class="font-semibold">{{$registration->id}}</span>
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
                Lab Teacher Initial: <span class="font-semibold">{{$registration->lab_teacher_name}}</span>
            </p>
            <p>
                Charged amount: <span class="font-semibold">{{$registration->extra['charged_amount']??''}}</span>
            </p>
            <p>
                payment method: <span class="font-semibold">{{$registration->extra['payment_method']??''}}</span>
            </p>
            <p>
                Transaction id: <span class="font-semibold">{{$registration->extra['transaction_id']??''}}</span>
            </p>
{{--            <div class="text-success-500 text-info-500"></div>--}}
            <p>
                Registration status: <span class="font-semibold text-lg text-{{$registration->status->getColor()}}-500">{{$registration->status}}</span>
            </p>



        </div>
    </div>
</x-web-layout>
