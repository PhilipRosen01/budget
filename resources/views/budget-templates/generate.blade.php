<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Generate Budgets from Templates') }}
            </h2>
            <a href="{{ route('budget-templates.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                ← Back to Templates
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Generate Budgets</h3>
                        <p class="text-sm text-gray-600">
                            Select a month and year to generate budgets from your active templates. 
                            This will create budgets for each active template for the selected month.
                        </p>
                    </div>

                    @if($templates->count() === 0)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <div class="flex">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">No Active Templates</h3>
                                    <p class="mt-1 text-sm text-yellow-700">
                                        You don't have any active templates. Please create or activate templates first.
                                    </p>
                                    <div class="mt-3">
                                        <a href="{{ route('budget-templates.create') }}" class="text-sm font-medium text-yellow-800 hover:text-yellow-900">
                                            Create Template →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <h4 class="text-sm font-medium text-blue-900 mb-2">Active Templates ({{ $templates->count() }})</h4>
                            <ul class="text-sm text-blue-800 space-y-1">
                                @foreach($templates as $template)
                                    <li class="flex justify-between">
                                        <span>• {{ $template->name }}</span>
                                        <span class="font-semibold">${{ number_format($template->amount, 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <form action="{{ route('budget-templates.generate-for-month') }}" method="POST" class="space-y-6">
                            @csrf

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Month Selection -->
                                <div>
                                    <label for="month" class="block text-sm font-medium text-gray-700 mb-2">Month</label>
                                    <select name="month" id="month" required class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        @php
                                            $currentMonth = now()->month;
                                        @endphp
                                        @foreach(range(1, 12) as $m)
                                            <option value="{{ $m }}" {{ $m == $currentMonth ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::create(null, $m, 1)->format('F') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Year Selection -->
                                <div>
                                    <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                                    <select name="year" id="year" required class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        @php
                                            $currentYear = now()->year;
                                        @endphp
                                        @foreach(range($currentYear - 1, $currentYear + 2) as $y)
                                            <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">What happens next?</h4>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    <li>• Budgets will be created for the selected month from all active templates</li>
                                    <li>• If a budget already exists for a template, it will be skipped</li>
                                    <li>• You can track spending for each budget once created</li>
                                </ul>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('budget-templates.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                                    Cancel
                                </a>
                                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition font-semibold">
                                    Generate Budgets
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
