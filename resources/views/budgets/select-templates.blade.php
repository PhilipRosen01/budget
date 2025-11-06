<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Select Budget Templates') }} - {{ $monthName }}
            </h2>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($templates->count() > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Choose which templates to use for {{ $monthName }}</h3>
                            <p class="text-sm text-gray-600">Select the budget templates you want to create budgets from. Templates that already have budgets for this month are marked and cannot be selected again.</p>
                        </div>

                        <form method="POST" action="{{ route('budgets.create-from-selected') }}">
                            @csrf
                            <input type="hidden" name="month" value="{{ $month }}">
                            <input type="hidden" name="year" value="{{ $year }}">

                            <div class="space-y-4">
                                @foreach($templates as $template)
                                    @php
                                        $hasExistingBudget = in_array($template->id, $existingBudgets);
                                    @endphp
                                    <div class="flex items-start space-x-3 p-4 border rounded-lg {{ $hasExistingBudget ? 'bg-gray-50 border-gray-200' : 'border-gray-300 hover:border-indigo-300' }}">
                                        <div class="flex items-center h-5">
                                            <input
                                                id="template_{{ $template->id }}"
                                                name="template_ids[]"
                                                type="checkbox"
                                                value="{{ $template->id }}"
                                                {{ $hasExistingBudget ? 'disabled checked' : '' }}
                                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                            >
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <label for="template_{{ $template->id }}" class="block text-sm font-medium {{ $hasExistingBudget ? 'text-gray-500' : 'text-gray-900' }}">
                                                {{ $template->name }}
                                                @if($hasExistingBudget)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                                                        Already Created
                                                    </span>
                                                @endif
                                            </label>
                                            <div class="mt-1 text-sm {{ $hasExistingBudget ? 'text-gray-400' : 'text-gray-500' }}">
                                                <div class="flex items-center justify-between">
                                                    <span>${{ number_format($template->amount, 2) }}</span>
                                                    @if($template->category)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $hasExistingBudget ? 'bg-gray-100 text-gray-500' : 'bg-blue-100 text-blue-800' }}">
                                                            {{ ucfirst($template->category) }}
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($template->description)
                                                    <p class="mt-1">{{ $template->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6 flex items-center justify-between">
                                <div>
                                    <button type="button" id="select-all" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                                        Select All Available
                                    </button>
                                    <span class="mx-2 text-gray-300">|</span>
                                    <button type="button" id="select-none" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                                        Select None
                                    </button>
                                </div>
                                
                                <div class="flex space-x-3">
                                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Cancel
                                    </a>
                                    <x-primary-button>
                                        Create Selected Budgets
                                    </x-primary-button>
                                </div>
                            </div>
                        </form>

                        <script>
                            document.getElementById('select-all').addEventListener('click', function() {
                                document.querySelectorAll('input[name="template_ids[]"]:not(:disabled)').forEach(cb => cb.checked = true);
                            });
                            
                            document.getElementById('select-none').addEventListener('click', function() {
                                document.querySelectorAll('input[name="template_ids[]"]:not(:disabled)').forEach(cb => cb.checked = false);
                            });
                        </script>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No Budget Templates</h3>
                            <p class="mt-1 text-sm text-gray-500">You need to create budget templates first before you can generate budgets.</p>
                            <div class="mt-6">
                                <a href="{{ route('budget-templates.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Create Budget Template
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>