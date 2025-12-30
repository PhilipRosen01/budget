<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Monthly Budgets - {{ $currentDate->format('F Y') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Track your budgets for this month</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('budgets.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to History
                </a>
                <a href="{{ route('budget-templates.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Manage Templates
                </a>
                <a href="{{ route('budgets.create', ['month' => $month, 'year' => $year]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Budget
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Month Navigation -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Browse by Month</h3>
                            <p class="text-sm text-gray-600 mt-1">View and manage budgets for different months</p>
                        </div>
                        <div class="flex space-x-2">
                            @if(count($availableMonths) > 1)
                                <select onchange="window.location.href='{{ route('budgets.month') }}?month=' + this.value.split('-')[0] + '&year=' + this.value.split('-')[1]" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach($availableMonths as $monthData)
                                        <option value="{{ $monthData['month'] }}-{{ $monthData['year'] }}" {{ $monthData['month'] == $month && $monthData['year'] == $year ? 'selected' : '' }}>
                                            {{ $monthData['display'] }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                            <span class="text-sm text-gray-500 self-center">
                                {{ $budgets->count() }} budget{{ $budgets->count() !== 1 ? 's' : '' }} found
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            @if($budgets->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($budgets as $budget)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $budget->name }}</h3>
                                        <div class="flex items-center space-x-2">
                                            @if($budget->category)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $budget->category }}
                                                </span>
                                            @endif
                                            @if($budget->budgetTemplate)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    From Template
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Custom
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('budgets.edit', $budget) }}" class="text-blue-600 hover:text-blue-900">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('budgets.destroy', $budget) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this budget?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="text-2xl font-bold text-gray-900">${{ number_format($budget->amount, 2) }}</div>
                                    <div class="text-sm text-gray-500">budgeted</div>
                                </div>

                                @if($budget->description)
                                    <p class="text-sm text-gray-600 mb-4">{{ $budget->description }}</p>
                                @endif

                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Spent:</span>
                                        <span class="font-semibold text-red-600">${{ number_format($budget->purchases->sum('amount'), 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Remaining:</span>
                                        <span class="font-semibold {{ $budget->amount - $budget->purchases->sum('amount') >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            ${{ number_format($budget->amount - $budget->purchases->sum('amount'), 2) }}
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        @php
                                            $percentage = $budget->amount > 0 ? ($budget->purchases->sum('amount') / $budget->amount) * 100 : 0;
                                            $percentage = min($percentage, 100);
                                        @endphp
                                        <div class="h-2 rounded-full {{ $percentage >= 90 ? 'bg-red-500' : ($percentage >= 70 ? 'bg-yellow-500' : 'bg-green-500') }}" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>

                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <a href="{{ route('budgets.show', $budget) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                        View Details & Purchases →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="mx-auto w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No budgets for {{ $currentDate->format('F Y') }}</h3>
                        <p class="text-gray-500 mb-4">Create budgets from your templates or add a custom budget for this month.</p>
                        <div class="flex justify-center space-x-4">
                            <a href="{{ route('budget-templates.generate-form') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                                Generate from Templates
                            </a>
                            <a href="{{ route('budgets.create', ['month' => $month, 'year' => $year]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                Add Custom Budget
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
