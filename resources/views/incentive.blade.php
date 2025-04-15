<x-web-layout>
    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <!-- Header with gradient background -->
                <div class="text-center mb-10 bg-gradient-to-r from-blue-600 to-indigo-700 dark:from-blue-800 dark:to-indigo-900 p-10 rounded-lg shadow-lg">
                    <h1 class="text-4xl font-bold text-white mb-3">Incentive Application</h1>
                    <p class="text-lg text-gray-100">
                        Complete this form to submit your course incentive application
                    </p>
                </div>

                <!-- Form Card with improved styling -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700 transition-all duration-300">
                    <div class="p-8">
                        <livewire:incentive-form/>
                    </div>
                </div>

                <!-- Additional Information with dark mode support -->
                <div class="mt-10 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-200 dark:border-gray-700 transition-all duration-300">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Important Information</h3>
                    <ul class="list-disc pl-6 text-gray-600 dark:text-gray-300 space-y-3">
                        <li>Make sure to provide accurate teacher information</li>
                        <li>All fields are required to process your application</li>
                        <li>Your performance and participation info will be collected from <a class="text-blue-500" href="https://diuacm.com">diuacm.com</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-web-layout>
