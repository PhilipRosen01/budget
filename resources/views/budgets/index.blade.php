<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Monthly Budgets - {{ $currentDate->format('F Y') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('budget-templates.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Manage Templates
                </a>
                <a href="{{ route('budgets.create', ['month' => $month, 'year' => $year]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Custom Budget
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
                                <select onchange="window.location.href='{{ route('budgets.index') }}?month=' + this.value.split('-')[0] + '&year=' + this.value.split('-')[1]" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
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
                                        <form action="{{ route('budgets.destroy', $budget) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this budget for {{ $budget->monthName }} {{ $budget->year }}?')">
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
                                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                                        <span>Spent: ${{ number_format($budget->totalSpent(), 2) }}</span>
                                        <span>Budget: ${{ number_format($budget->amount, 2) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        @php $percentage = $budget->percentageUsed() @endphp
                                        <div class="bg-{{ $percentage > 90 ? 'red' : ($percentage > 75 ? 'yellow' : 'green') }}-600 h-2.5 rounded-full" style="width: {{ min($percentage, 100) }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ number_format($percentage, 1) }}% used
                                        @if($budget->remainingBudget() >= 0)
                                            • ${{ number_format($budget->remainingBudget(), 2) }} remaining
                                        @else
                                            • ${{ number_format(abs($budget->remainingBudget()), 2) }} over budget
                                        @endif
                                    </p>
                                </div>

                                <div class="text-sm text-gray-600">
                                    <p class="font-medium">{{ $budget->fullMonthYear }}</p>
                                    @if($budget->isCurrentMonth())
                                        <p class="text-green-600 text-xs">Current Month</p>
                                    @endif
                                    <p class="mt-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $budget->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $budget->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </p>
                                </div>

                                @if($budget->description)
                                    <p class="text-sm text-gray-600 mt-3">{{ $budget->description }}</p>
                                @endif

                                <div class="mt-4 flex justify-between">
                                    <a href="{{ route('budgets.show', $budget) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                        View Details →
                                    </a>
                                    <span class="text-xs text-gray-500">
                                        {{ $budget->purchases->count() }} purchase{{ $budget->purchases->count() !== 1 ? 's' : '' }}
                                    </span>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No budgets yet</h3>
                        <p class="text-gray-500 mb-4">Create your first budget to start tracking your income and expenses.</p>
                        <a href="{{ route('budgets.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Create Your First Budget
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>