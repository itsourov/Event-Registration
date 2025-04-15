<div class="w-full">
    <form wire:submit="create" class="space-y-8">
        <div class="bg-gray-50 dark:bg-gray-800/30 p-6 rounded-lg border border-gray-100 dark:border-gray-700">
            {{ $this->form }}
        </div>

        <div class="flex justify-center">
            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 dark:from-blue-700 dark:to-indigo-800 dark:hover:from-blue-800 dark:hover:to-indigo-900 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center group">
                <span class="text-lg">Submit Application</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </form>

    <x-filament-actions::modals />
</div>
